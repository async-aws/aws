<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Provides Credentials from the running EC2 metadata server using the IMDS version 1.
 *
 * @see https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/instancedata-data-retrieval.html
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class InstanceProvider implements CredentialProvider
{
    private const ENDPOINT = 'http://169.254.169.254/latest/meta-data/iam/security-credentials';

    private $logger;

    private $httpClient;

    private $timeout;

    public function __construct(?HttpClientInterface $httpClient = null, ?LoggerInterface $logger = null, float $timeout = 1.0)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->timeout = $timeout;
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        // fetch current Profile
        try {
            $response = $this->httpClient->request('GET', self::ENDPOINT, ['timeout' => $this->timeout]);
            $profile = $response->getContent();
        } catch (TransportExceptionInterface $e) {
            $this->logger->info('Failed to fetch Profile from Instance Metadata.', ['exception' => $e]);

            return null;
        } catch (HttpExceptionInterface $e) {
            $this->logger->info('Failed to fetch Profile from Instance Metadata.', ['exception' => $e]);

            return null;
        }

        // fetch credentials from profile
        try {
            $response = $this->httpClient->request('GET', self::ENDPOINT . '/' . $profile, ['timeout' => 1.0]);
            $result = $response->toArray();
            if ('Success' !== $result['Code']) {
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
}
