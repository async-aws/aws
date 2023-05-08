<?php

namespace AsyncAws\Core\HttpClient;

use Symfony\Component\HttpClient\CurlHttpClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class AwsHttpClientFactory
{
    public static function createClient(): HttpClientInterface
    {
        return new CurlHttpClient([
            'extra' => [
                'curl' => [
                    \CURLOPT_PROTOCOLS => \CURLPROTO_HTTPS,
                    \CURLOPT_REDIR_PROTOCOLS => \CURLPROTO_HTTPS,
                    \CURLOPT_SSLVERSION => \CURL_SSLVERSION_TLSv1_2,
                ],
            ],
        ]);
    }

    public static function createRetryableClient(HttpClientInterface $httpClient = null, LoggerInterface $logger = null): HttpClientInterface
    {
        if (null === $httpClient) {
            $httpClient = self::createClient();
        }

        if (class_exists(RetryableHttpClient::class)) {
            /** @psalm-suppress MissingDependency */
            $httpClient = new RetryableHttpClient(
                $httpClient,
                new AwsRetryStrategy(),
                3,
                $logger
            );
        }

        return $httpClient;
    }
}
