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
    private const ENV_URI = 'AWS_CONTAINER_CREDENTIALS_RELATIVE_URI';
    private const ENV_TIMEOUT = 'AWS_METADATA_SERVICE_TIMEOUT';

    private $logger;

    private $httpClient;

    private $timeout;

    private $envUri;

    public function __construct(?HttpClientInterface $httpClient = null, ?LoggerInterface $logger = null, float $timeout = 1.0)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->timeout = getenv(self::ENV_TIMEOUT) ?: $timeout;
        $this->envUri = getenv(self::ENV_URI) ?: '';
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        // fetch credentials from ecs endpoint
        try {
            $response = $this->httpClient->request('GET', self::ENDPOINT . $this->envUri, ['timeout' => $this->timeout]);
            $result = $this->toArray($response);

            if (200 != $response->getStatusCode()) {
                $this->logger->info('Unexpected instance profile.', ['response_code' => $result['Code']]);

                return null;
            }
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

    /**
     * Copy of Symfony\Component\HttpClient\Response::toArray without assertion on Content-Type header.
     */
    private function toArray(ResponseInterface $response): array
    {
        if ('' === $content = $response->getContent(true)) {
            throw new TransportException('Response body is empty.');
        }

        try {
            $content = json_decode($content, true, 512, \JSON_BIGINT_AS_STRING | (\PHP_VERSION_ID >= 70300 ? \JSON_THROW_ON_ERROR : 0));
        } catch (\JsonException $e) {
            /** @psalm-suppress all */
            throw new JsonException(sprintf('%s for "%s".', $e->getMessage(), $response->getInfo('url')), $e->getCode());
        }

        if (\PHP_VERSION_ID < 70300 && \JSON_ERROR_NONE !== json_last_error()) {
            /** @psalm-suppress InvalidArgument */
            throw new JsonException(sprintf('%s for "%s".', json_last_error_msg(), $response->getInfo('url')), json_last_error());
        }

        if (!\is_array($content)) {
            /** @psalm-suppress InvalidArgument */
            throw new JsonException(sprintf('JSON content was expected to decode to an array, %s returned for "%s".', \gettype($content), $response->getInfo('url')));
        }

        return $content;
    }
}
