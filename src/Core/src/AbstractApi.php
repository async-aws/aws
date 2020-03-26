<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Credentials\CacheProvider;
use AsyncAws\Core\Credentials\ChainProvider;
use AsyncAws\Core\Credentials\ConfigurationProvider;
use AsyncAws\Core\Credentials\CredentialProvider;
use AsyncAws\Core\Credentials\IniFileProvider;
use AsyncAws\Core\Credentials\InstanceProvider;
use AsyncAws\Core\Credentials\WebIdentityProvider;
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
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @var Signer
     */
    private $signer;

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
        $this->credentialProvider = $credentialProvider ?? new CacheProvider(new ChainProvider([
            new ConfigurationProvider(),
            new WebIdentityProvider($this->logger),
            new IniFileProvider($this->logger),
            new InstanceProvider($this->httpClient, $this->logger),
        ]));
    }

    final public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    final public function presign(Input $input, ?\DateTimeImmutable $expires = null): string
    {
        $request = $input->request();
        $request->setEndpoint($this->getEndpoint($request->getUri(), $request->getQuery()));

        if (null !== $credentials = $this->credentialProvider->getCredentials($this->configuration)) {
            $this->getSigner()->presign($request, $credentials, new RequestContext(['expirationDate' => $expires]));
        }

        return $request->getEndpoint();
    }

    abstract protected function getServiceCode(): string;

    abstract protected function getSignatureVersion(): string;

    abstract protected function getSignatureScopeName(): string;

    final protected function getResponse(Request $request, ?RequestContext $context = null): Response
    {
        $request->setEndpoint($this->getEndpoint($request->getUri(), $request->getQuery()));

        if (null !== $credentials = $this->credentialProvider->getCredentials($this->configuration)) {
            $this->getSigner()->sign($request, $credentials, $context ?? new RequestContext());
        }

        $length = $request->getBody()->length();
        if (null !== $length && !$request->hasHeader('content-length')) {
            $request->setHeader('content-length', $length);
        }

        // Some servers (like testing Docker Images) does not supports `Transfer-Encoding: chunked` requests.
        // The body is converted into string to prevent curl using `Transfer-Encoding: chunked` unless it really has to.
        if (($requestBody = $request->getBody()) instanceof StringStream) {
            $requestBody = $requestBody->stringify();
        }

        $response = $this->httpClient->request($request->getMethod(), $request->getEndpoint(), ['headers' => $request->getHeaders(), 'body' => 0 === $length ? null : $requestBody]);

        return new Response($response, $this->httpClient);
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
     * Fallback function for getting the endpoint. This could be overridden by any APIClient.
     *
     * @param string $uri   or path
     * @param array  $query parameters that should go in the query string
     */
    private function getEndpoint(string $uri, array $query): string
    {
        /** @psalm-suppress PossiblyNullArgument */
        $endpoint = strtr($this->configuration->get('endpoint'), [
            '%region%' => $this->configuration->get('region'),
            '%service%' => $this->getServiceCode(),
        ]);
        $endpoint .= $uri;
        if (empty($query)) {
            return $endpoint;
        }

        return $endpoint . (false === \strpos($endpoint, '?') ? '?' : '&') . http_build_query($query);
    }

    private function getSigner()
    {
        if (null === $this->signer) {
            /** @var string $region */
            $region = $this->configuration->get(Configuration::OPTION_REGION);
            $factories = $this->getSignerFactories();
            if (!isset($factories[$signatureVersion = $this->getSignatureVersion()])) {
                throw new InvalidArgument(sprintf('The signature "%s" is not implemented.', $signatureVersion));
            }

            $this->signer = $factories[$signatureVersion]($this->getSignatureScopeName(), $region);
        }

        return $this->signer;
    }
}
