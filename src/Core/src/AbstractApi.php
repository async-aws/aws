<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Credentials\CacheProvider;
use AsyncAws\Core\Credentials\ChainProvider;
use AsyncAws\Core\Credentials\CredentialProvider;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Signer\Signer;
use AsyncAws\Core\Signer\SignerV4;
use AsyncAws\Core\Stream\StringStream;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Base class all API clients are inheriting.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
abstract class AbstractApi
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var CredentialProvider
     */
    private $credentialProvider;

    /**
     * @var Signer[]
     */
    private $signers;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Configuration|array $configuration
     */
    public function __construct($configuration = [], ?CredentialProvider $credentialProvider = null, ?HttpClientInterface $httpClient = null, ?LoggerInterface $logger = null)
    {
        if (\is_array($configuration)) {
            $configuration = Configuration::create($configuration);
        } elseif (!$configuration instanceof Configuration) {
            throw new InvalidArgument(sprintf('Second argument to "%s::__construct()" must be an array or an instance of "%s"', __CLASS__, Configuration::class));
        }

        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->logger = $logger ?? new NullLogger();
        $this->configuration = $configuration;
        $this->credentialProvider = $credentialProvider ?? new CacheProvider(ChainProvider::createDefaultChain($this->httpClient, $this->logger));
    }

    final public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    final public function presign(Input $input, ?\DateTimeImmutable $expires = null): string
    {
        $request = $input->request();
        $request->setEndpoint($this->getEndpoint($request->getUri(), $request->getQuery(), $input->getRegion()));

        if (null !== $credentials = $this->credentialProvider->getCredentials($this->configuration)) {
            $this->getSigner($input->getRegion())->presign($request, $credentials, new RequestContext(['expirationDate' => $expires]));
        }

        return $request->getEndpoint();
    }

    abstract protected function getServiceCode(): string;

    abstract protected function getSignatureVersion(): string;

    abstract protected function getSignatureScopeName(): string;

    final protected function getResponse(Request $request, ?RequestContext $context = null): Response
    {
        $request->setEndpoint($this->getEndpoint($request->getUri(), $request->getQuery(), $context ? $context->getRegion() : null));

        if (null !== $credentials = $this->credentialProvider->getCredentials($this->configuration)) {
            $this->getSigner($context ? $context->getRegion() : null)->sign($request, $credentials, $context ?? new RequestContext());
        }

        $length = $request->getBody()->length();
        if (null !== $length && !$request->hasHeader('content-length')) {
            $request->setHeader('content-length', (string) $length);
        }

        // Some servers (like testing Docker Images) does not supports `Transfer-Encoding: chunked` requests.
        // The body is converted into string to prevent curl using `Transfer-Encoding: chunked` unless it really has to.
        if (($requestBody = $request->getBody()) instanceof StringStream) {
            $requestBody = $requestBody->stringify();
        }

        $response = $this->httpClient->request(
            $request->getMethod(),
            $request->getEndpoint(),
            [
                'headers' => $request->getHeaders(),
                'body' => 0 === $length ? null : $requestBody,
            ]
        );

        return new Response($response, $this->httpClient, $this->logger);
    }

    /**
     * @return callable[]
     */
    protected function getSignerFactories(): array
    {
        return [
            'v4' => static function (string $service, string $region) {
                return new SignerV4($service, $region);
            },
        ];
    }

    /**
     * Returns the AWS endpoint metadata for the given region.
     * When user did not provide a region, the client have to either return a global endpoint or fallback to
     * the Configuration::DEFAULT_REGION constant.
     *
     * This implementation is a BC layer for client that does not require core:^1.2.
     *
     * @param ?string $region region provided by the user (without fallback to a default region)
     *
     * @return array{endpoint: string, signRegion: string, signService: string, signVersions: string[]}
     */
    protected function getEndpointMetadata(?string $region): array
    {
        /** @var string $endpoint */
        $endpoint = $this->configuration->get('endpoint');
        /** @var string $region */
        $region = $region ?? $this->configuration->get('region');

        return [
            'endpoint' => $endpoint,
            'signRegion' => $region,
            'signService' => $this->getSignatureScopeName(),
            'signVersions' => [$this->getSignatureVersion()],
        ];
    }

    /**
     * Build the endpoint full uri.
     *
     * @param string  $uri    or path
     * @param array   $query  parameters that should go in the query string
     * @param ?string $region region provided by the user in the `@region` parameter of the Input
     */
    private function getEndpoint(string $uri, array $query, ?string $region): string
    {
        /** @var string $region */
        $region = $region ?? $this->configuration->isDefault('region') ? null : $this->configuration->get('region');
        $metadata = $this->getEndpointMetadata($region);
        if (!$this->configuration->isDefault('endpoint')) {
            $endpoint = $this->configuration->get('endpoint');
        } else {
            $endpoint = $metadata['endpoint'];
        }

        /** @psalm-suppress PossiblyNullArgument */
        $endpoint = strtr($endpoint, [
            '%region%' => $region ?? $this->configuration->get('region'),
            '%service%' => $this->getServiceCode(), // if people provides a custom endpoint 'http://%service%.localhost/
        ]);

        $endpoint .= $uri;
        if (empty($query)) {
            return $endpoint;
        }

        return $endpoint . (false === \strpos($endpoint, '?') ? '?' : '&') . http_build_query($query);
    }

    /**
     * @param ?string $region region provided by the user in the `@region` parameter of the Input
     */
    private function getSigner(?string $region)
    {
        /** @var string $region */
        $region = $region ?? $this->configuration->isDefault('region') ? null : $this->configuration->get('region');
        if (!isset($this->signers[$region])) {
            $metadata = $this->getEndpointMetadata($region);
            $factories = $this->getSignerFactories();
            $factory = null;
            foreach ($metadata['signVersions'] as $signatureVersion) {
                if (isset($factories[$signatureVersion])) {
                    $factory = $factories[$signatureVersion];

                    break;
                }
            }

            if (null === $factory) {
                throw new InvalidArgument(sprintf('None of the signatures "%s" is implemented.', \implode(', ', $metadata['signVersions'])));
            }

            $this->signers[$region] = $factory($metadata['signService'], $metadata['signRegion']);
        }

        /** @psalm-suppress PossiblyNullArrayOffset */
        return $this->signers[$region];
    }
}
