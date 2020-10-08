<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;
use AsyncAws\Core\Sts\StsClient;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Provides Credentials from Configuration data.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class ConfigurationProvider implements CredentialProvider
{
    private $logger;

    private $httpClient;

    public function __construct(?LoggerInterface $logger = null, ?HttpClientInterface $httpClient = null)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->httpClient = $httpClient;
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        if (
            $configuration->has(Configuration::OPTION_ACCESS_KEY_ID)
            && $configuration->has(Configuration::OPTION_SECRET_ACCESS_KEY)
        ) {
            if ($configuration->has(Configuration::OPTION_ROLE_ARN)) {
                /** @psalm-suppress PossiblyNullArgument */
                return $this->getCredentialsFromRole(
                    $configuration->get(Configuration::OPTION_ROLE_ARN),
                    $configuration->get(Configuration::OPTION_ROLE_SESSION_NAME),
                    $configuration->get(Configuration::OPTION_REGION)
                );
            }

            /** @psalm-suppress PossiblyNullArgument */
            return new Credentials(
                $configuration->get(Configuration::OPTION_ACCESS_KEY_ID),
                $configuration->get(Configuration::OPTION_SECRET_ACCESS_KEY),
                $configuration->get(Configuration::OPTION_SESSION_TOKEN)
            );
        }

        return null;
    }

    private function getCredentialsFromRole(string $roleArn, ?string $sessionName, ?string $region): ?Credentials
    {
        $sessionName = $sessionName ?? \uniqid('async-aws-', true);

        $stsClient = new StsClient(['region' => $region], new NullProvider(), $this->httpClient);
        $result = $stsClient->assumeRole([
            'RoleArn' => $roleArn,
            'RoleSessionName' => $sessionName,
        ]);

        try {
            if (null === $credentials = $result->getCredentials()) {
                throw new \RuntimeException('The AsumeRole response does not contains credentials');
            }
        } catch (\Exception $e) {
            $this->logger->warning('Failed to get credentials from assumed role: {exception}".', ['exception' => $e]);

            return null;
        }

        $date = null;
        if ((null !== $response = $result->info()['response'] ?? null) && null !== $date = $response->getHeaders(false)['date'][0] ?? null) {
            $date = new \DateTimeImmutable($date);
        }

        return new Credentials(
            $credentials->getAccessKeyId(),
            $credentials->getSecretAccessKey(),
            $credentials->getSessionToken(),
            Credentials::adjustExpireDate($credentials->getExpiration(), $date)
        );
    }
}
