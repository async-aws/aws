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
use AsyncAws\Core\Signer\Request;
use AsyncAws\Core\Signer\Signer;
use AsyncAws\Core\Signer\SignerV4;
use AsyncAws\Core\Stream\StreamFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Base class most APIs are inheriting.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class AbstractApi
{
    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var CredentialProvider
     */
    protected $credentialProvider;

    /**
     * @var LoggerInterface|null
     */
    protected $logger;

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

    /**
     * @param string[]|string[][]                    $headers headers names provided as keys or as part of values
     * @param string|resource|callable|iterable|null $body
     */
    final public function request(string $method, $body = '', $headers = [], ?string $endpoint = null): Result
    {
        $response = $this->getResponse($method, $body, $headers, $endpoint);

        return new Result($response, $this->httpClient);
    }

    abstract protected function getServiceCode(): string;

    abstract protected function getSignatureVersion(): string;

    abstract protected function getSignatureScopeName(): string;

    /**
     * @param string[]|string[][]                    $headers headers names provided as keys or as part of values
     * @param string|resource|callable|iterable|null $body
     */
    final protected function getResponse(string $method, $body, $headers = [], ?string $endpoint = null): ResponseInterface
    {
        if (!isset($headers['content-type'])) {
            $headers['content-type'] = 'text/plain';
        }

        $stream = StreamFactory::create($body);

        $request = new Request($method, $this->fillEndpoint($endpoint), $headers, $stream);
        $this->getSigner()->sign($request, $this->credentialProvider->getCredentials($this->configuration));

        if (!$request->hasHeader('content-length') && null !== $length = $request->getBody()->length()) {
            $request->setHeader('content-length', $length);
        }

        return $this->httpClient->request($request->getMethod(), $request->getUrl(), ['headers' => $request->getHeaders(), 'body' => $request->getBody()]);
    }

    /**
     * Fallback function for getting the endpoint. This could be overridden by any APIClient.
     *
     * @param string $uri   or path
     * @param array  $query parameters that should go in the query string
     */
    protected function getEndpoint(string $uri, array $query): ?string
    {
        /** @var string $endpoint */
        $endpoint = $this->configuration->get('endpoint');
        $endpoint .= $uri;
        if (empty($query)) {
            return $endpoint;
        }

        return $endpoint . (false === \strpos($endpoint, '?') ? '?' : '&') . http_build_query($query);
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

    private function fillEndpoint(?string $endpoint): string
    {
        /** @var string $endpoint */
        $endpoint = $endpoint ?? $this->configuration->get('endpoint');

        return strtr($endpoint, [
            '%region%' => $this->configuration->get('region'),
            '%service%' => $this->getServiceCode(),
        ]);
    }
}
