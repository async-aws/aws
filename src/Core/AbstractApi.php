<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Credentials\CacheProvider;
use AsyncAws\Core\Credentials\ChainProvider;
use AsyncAws\Core\Credentials\ConfigurationProvider;
use AsyncAws\Core\Credentials\CredentialProvider;
use AsyncAws\Core\Credentials\IniFileProvider;
use AsyncAws\Core\Credentials\InstanceProvider;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Signers\Request;
use AsyncAws\Core\Signers\Signer;
use AsyncAws\Core\Signers\SignerV4;
use AsyncAws\Core\Signers\SignerV4ForS3;
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
     * @var Signer
     */
    private $signer;

    /**
     * @param Configuration|array $configuration
     */
    public function __construct($configuration = [], ?CredentialProvider $credentialProvider = null, ?HttpClientInterface $httpClient = null)
    {
        if (\is_array($configuration)) {
            $configuration = Configuration::create($configuration);
        } elseif (!$configuration instanceof Configuration) {
            throw new InvalidArgument(sprintf('Second argument to "%s::__construct()" must be an array or an instance of "%s"', __CLASS__, Configuration::class));
        }

        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->configuration = $configuration;
        $this->credentialProvider = $credentialProvider ?? new CacheProvider(new ChainProvider([
            new ConfigurationProvider(),
            new IniFileProvider(),
            new InstanceProvider(),
        ]));
    }

    /**
     * @param string[]|string[][]                         $headers headers names provided as keys or as part of values
     * @param array|string|resource|\Traversable|\Closure $body
     */
    final public function request(string $method, $body = '', $headers = [], ?string $endpoint = null): Result
    {
        $response = $this->getResponse($method, $body, $headers, $endpoint);

        return new Result($response);
    }

    abstract protected function getServiceCode(): string;

    abstract protected function getSignatureVersion(): string;

    /**
     * @param string[]|string[][]                         $headers headers names provided as keys or as part of values
     * @param array|string|resource|\Traversable|\Closure $body
     */
    final protected function getResponse(string $method, $body, $headers = [], ?string $endpoint = null): ResponseInterface
    {
        if (\is_array($body)) {
            $body = http_build_query($body, '', '&', \PHP_QUERY_RFC1738);
            $headers['content-type'] = 'application/x-www-form-urlencoded';
        }

        $request = new Request($method, $this->fillEndpoint($endpoint), $headers, $body);
        $this->getSigner()->Sign($request, $this->credentialProvider->getCredentials($this->configuration));

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

    private function getSigner()
    {
        if (null === $this->signer) {
            /** @var string $region */
            $region = $this->configuration->get(Configuration::OPTION_REGION);
            switch ($signatureVersion = $this->getSignatureVersion()) {
                case 'v4':
                    $this->signer = new SignerV4($this->getServiceCode(), $region);

                    break;
                case 's3':
                    $this->signer = new SignerV4ForS3($this->getServiceCode(), $region);

                    break;
                default:
                    throw new InvalidArgument(sprintf('The signature "%s" is not implemented.', $signatureVersion));
            }
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
