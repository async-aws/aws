<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use WorkingTitle\Aws\Exception\InvalidArgument;

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

    abstract protected function getServiceCode(): string;

    /**
     * @param Configuration|array $configuration
     */
    public function __construct(HttpClientInterface $httpClient, $configuration)
    {
        if (is_array($configuration)) {
            $configuration = Configuration::create($configuration);
        } elseif (!$configuration instanceof Configuration) {
            throw new InvalidArgument(sprintf('Second argument to "%s::__construct()" must be an array or an instance of "%s"', __CLASS__, Configuration::class));
        }

        $this->httpClient = $httpClient;
        $this->configuration = $configuration;
    }

    /**
     * @param iterable|string[]|string[][]                $headers headers names provided as keys or as part of values
     * @param array|string|resource|\Traversable|\Closure $body
     *
     * @return ResultPromise<ResponseInterface>
     */
    public function request(string $method, $body = '', $headers = [], ?string $endpoint = null): ResultPromise
    {
        $response = $this->getResponse($method, $body, $headers, $endpoint);

        return new ResultPromise($response, ResponseInterface::class);
    }

    /**
     * @param iterable|string[]|string[][]                $headers headers names provided as keys or as part of values
     * @param array|string|resource|\Traversable|\Closure $body
     */
    protected function getResponse(string $method, $body, $headers = [], ?string $endpoint = null): ResponseInterface
    {
        $date = gmdate('D, d M Y H:i:s e');
        $auth = sprintf(
            'AWS3-HTTPS AWSAccessKeyId=%s,Algorithm=HmacSHA256,Signature=%s',
            $this->configuration->get('accessKeyId'),
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
        return base64_encode(hash_hmac('sha256', $string, $this->configuration->get('accessKeySecret'), true));
    }

    private function getEndpoint(?string $endpoint): string
    {
        return strtr($endpoint ?? $this->configuration->get('endpoint'), [
            '%region%' => $this->configuration->get('region'),
            '%service%' => $this->getServiceCode(),
        ]);
    }
}
