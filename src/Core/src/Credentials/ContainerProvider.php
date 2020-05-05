<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Provides Credentials from the running ECS.
 *
 * @see https://docs.aws.amazon.com/AWSJavaSDK/latest/javadoc/index.html?com/amazonaws/auth/ContainerCredentialsProvider.html
 */
final class ContainerProvider implements CredentialProvider
{
    private const ENDPOINT = 'http://169.254.170.2';

    private $logger;

    private $httpClient;

    private $timeout;

    public function __construct(?HttpClientInterface $httpClient = null, ?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->httpClient = $httpClient ?? HttpClient::create();
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        $timeout = $configuration->get(Configuration::OPTION_METADATA_SERVICE_TIMEOUT);
        $envUri = $configuration->get(Configuration::OPTION_ECS_AUTH_ENDPOINT);
        // introduces an early exit if the env variable is not set.
        if (empty($envUri)) {
            return null;
        }
        // fetch credentials from ecs endpoint
        try {
            $response = $this->httpClient->request('GET', self::ENDPOINT . $envUri, ['timeout' => $timeout]);
            $result = $response->toArray();
        } catch (DecodingExceptionInterface $e) {
            $this->logger->info('Failed to decode Credentials.', ['exception' => $e]);

            return null;
        } catch (TransportExceptionInterface $e) {
            $this->logger->info('Failed to fetch Profile from Instance Metadata.', ['exception' => $e]);

            return null;
        } catch (HttpExceptionInterface $e) {
            $this->logger->info('Failed to fetch Profile from Instance Metadata.', ['exception' => $e]);

            return null;
        }

        return new Credentials(
            $result['AccessKeyId'],
            $result['SecretAccessKey'],
            $result['Token'],
            new \DateTimeImmutable($result['Expiration'])
        );
    }
}
