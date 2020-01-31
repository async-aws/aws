<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Credentials\CacheProvider;
use AsyncAws\Core\Credentials\ChainProvider;
use AsyncAws\Core\Credentials\ConfigurationProvider;
use AsyncAws\Core\Credentials\CredentialProvider;
use AsyncAws\Core\Credentials\IniFileProvider;
use AsyncAws\Core\Exception\InvalidArgument;
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

    abstract protected function getServiceCode(): string;

    /**
     * @param Configuration|array $configuration
     */
    public function __construct(HttpClientInterface $httpClient, $configuration, ?CredentialProvider $credentialProvider = null)
    {
        if (\is_array($configuration)) {
            $configuration = Configuration::create($configuration);
        } elseif (!$configuration instanceof Configuration) {
            throw new InvalidArgument(sprintf('Second argument to "%s::__construct()" must be an array or an instance of "%s"', __CLASS__, Configuration::class));
        }

        $this->httpClient = $httpClient;
        $this->configuration = $configuration;
        $this->credentialProvider = $credentialProvider ?? new CacheProvider(new ChainProvider([
            new ConfigurationProvider(),
            new IniFileProvider(),
        ]));
    }

    /**
     * @param iterable|string[]|string[][]                $headers headers names provided as keys or as part of values
     * @param array|string|resource|\Traversable|\Closure $body
     */
    public function request(string $method, $body = '', $headers = [], ?string $endpoint = null): Result
    {
        $response = $this->getResponse($method, $body, $headers, $endpoint);

        return new Result($response);
    }

    /**
     * @param iterable|string[]|string[][]                $headers headers names provided as keys or as part of values
     * @param array|string|resource|\Traversable|\Closure $body
     */
    protected function getResponse(string $method, $body, $headers = [], ?string $endpoint = null): ResponseInterface
    {
        $date = gmdate('D, d M Y H:i:s e');
        $credentials = $this->credentialProvider->getCredentials();
        $auth = sprintf(
            'AWS3-HTTPS AWSAccessKeyId=%s,Algorithm=HmacSHA256,Signature=%s',
            $credentials ? $credentials->getAccessKeyId() : '',
            $this->getSignature($date)
        );

        $headers = array_merge([
            'X-Amzn-Authorization' => $auth,
            'Date' => $date,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], $headers);

        $options = ['headers' => $headers, 'body' => $body];

        return $this->httpClient->request($method, $this->getEndpoint($endpoint), $options);
    }

    private function getSignature(string $string): string
    {
        $credentials = $this->credentialProvider->getCredentials();

        return base64_encode(hash_hmac('sha256', $string, $credentials ? $credentials->getSecretKey() : '', true));
    }

    private function getEndpoint(?string $endpoint): string
    {
        return strtr($endpoint ?? $this->configuration->get('endpoint'), [
            '%region%' => $this->configuration->get('region'),
            '%service%' => $this->getServiceCode(),
        ]);
    }
}
