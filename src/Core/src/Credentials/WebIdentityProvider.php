<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;
use AsyncAws\Core\Sts\StsClient;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Provides Credentials from Web Identity or OpenID Connect Federation.
 *
 * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles_create_for-idp_oidc.html
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class WebIdentityProvider implements CredentialProvider
{
    private $iniFileLoader;

    private $logger;

    private $httpClient;

    public function __construct(?LoggerInterface $logger = null, ?IniFileLoader $iniFileLoader = null, ?HttpClientInterface $httpClient = null)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->iniFileLoader = $iniFileLoader ?? new IniFileLoader($this->logger);
        $this->httpClient = $httpClient;
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        $roleArn = $configuration->get(Configuration::OPTION_ROLE_ARN);
        $tokenFile = $configuration->get(Configuration::OPTION_WEB_IDENTITY_TOKEN_FILE);

        if ($tokenFile && $roleArn) {
            return $this->getCredentialsFromRole(
                $roleArn,
                $tokenFile,
                $configuration->get(Configuration::OPTION_ROLE_SESSION_NAME),
                $configuration->get(Configuration::OPTION_REGION)
            );
        }

        $profilesData = $this->iniFileLoader->loadProfiles([
            $configuration->get(Configuration::OPTION_SHARED_CREDENTIALS_FILE),
            $configuration->get(Configuration::OPTION_SHARED_CONFIG_FILE),
        ]);
        if (empty($profilesData)) {
            return null;
        }

        /** @var string $profile */
        $profile = $configuration->get(Configuration::OPTION_PROFILE);
        if (!isset($profilesData[$profile])) {
            $this->logger->warning('Profile "{profile}" not found.', ['profile' => $profile]);

            return null;
        }

        $profileData = $profilesData[$profile];
        $roleArn = $profileData[IniFileLoader::KEY_ROLE_ARN] ?? null;
        $tokenFile = $profileData[IniFileLoader::KEY_WEB_IDENTITY_TOKEN_FILE] ?? null;

        if (null !== $roleArn && null !== $tokenFile) {
            return $this->getCredentialsFromRole(
                $roleArn,
                $tokenFile,
                $profileData[IniFileLoader::KEY_ROLE_SESSION_NAME] ?? null,
                $profileData[IniFileLoader::KEY_REGION] ?? $configuration->get(Configuration::OPTION_REGION)
            );
        }

        return null;
    }

    private function getCredentialsFromRole(string $roleArn, string $tokenFile, ?string $sessionName, ?string $region): ?Credentials
    {
        $sessionName = $sessionName ?? \uniqid('async-aws-', true);
        if (!preg_match("/^\w\:|^\/|^\\\/", $tokenFile)) {
            $this->logger->warning('WebIdentityTokenFile "{tokenFile}" must be an absolute path.', ['tokenFile' => $tokenFile]);
        }

        try {
            if (false === $token = file_get_contents($tokenFile)) {
                throw new \RuntimeException('failed to read data');
            }
        } catch (\Exception $e) {
            $this->logger->warning('"Error reading WebIdentityTokenFile "{tokenFile}.', ['tokenFile' => $tokenFile, 'exception' => $e]);

            return null;
        }

        $stsClient = new StsClient(['region' => $region], new NullProvider(), $this->httpClient);
        $result = $stsClient->assumeRoleWithWebIdentity([
            'RoleArn' => $roleArn,
            'RoleSessionName' => $sessionName,
            'WebIdentityToken' => $token,
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
            Credentials::adjustExpireDate($credentials->getExpiration(), $date),
            true
        );
    }
}
