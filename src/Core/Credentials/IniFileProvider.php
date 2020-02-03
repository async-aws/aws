<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;
use AsyncAws\Core\Sts\StsClient;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Provides Credentials from standard AWS ini file.
 *
 * @see https://docs.aws.amazon.com/cli/latest/userguide/cli-configure-files.html
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class IniFileProvider implements CredentialProvider
{
    private const KEY_ACCESS_KEY_ID = 'aws_access_key_id';
    private const KEY_SECRET_ACCESS_KEY = 'aws_secret_access_key';
    private const KEY_SESSION_TOKEN = 'aws_session_token';
    private const KEY_ROLE_ARN = 'role_arn';
    private const KEY_ROLE_SESSION_NAME = 'role_session_name';
    private const KEY_SOURCE_PROFILE = 'source_profile';

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        $profilesData = $this->loadProfiles([
            $configuration->get(Configuration::OPTION_SHARED_CREDENTIALS_FILE),
            $configuration->get(Configuration::OPTION_SHARED_CONFIG_FILE),
        ]);
        if (empty($profilesData)) {
            return null;
        }

        /** @var string $profile */
        $profile = $configuration->get(Configuration::OPTION_PROFILE);

        return $this->getCredentialsFromProfile($profilesData, $profile);
    }

    private function getCredentialsFromProfile(array $profilesData, string $profile, array $circularCollector = []): ?Credentials
    {
        if (isset($circularCollector[$profile])) {
            $this->logger->warning('Circular reference detected when loading "{profile}". Already loaded {previous_profiles}', ['profile' => $profile, 'previous_profiles' => \array_keys($circularCollector)]);

            return null;
        }
        $circularCollector[$profile] = true;

        $profileData = $profilesData[$profile];
        if (!isset($profilesData[$profile])) {
            $this->logger->warning('Profile "{profile}" not found.', ['profile' => $profile]);

            return null;
        }

        if (isset($profileData[self::KEY_ACCESS_KEY_ID], $profileData[self::KEY_ACCESS_KEY_ID])) {
            return new Credentials(
                $profileData[self::KEY_ACCESS_KEY_ID],
                $profileData[self::KEY_SECRET_ACCESS_KEY],
                $profileData[self::KEY_SESSION_TOKEN] ?? null
            );
        }

        if (isset($profileData[self::KEY_ROLE_ARN])) {
            return $this->getCredentialsFromRole($profilesData, $profileData, $profile, $circularCollector);
        }

        $this->logger->info('No credentials found for profile "{profile}".', ['profile' => $profile]);

        return null;
    }

    private function getCredentialsFromRole(array $profilesData, array $profileData, string $profile, array $circularCollector = []): ?Credentials
    {
        $roleArn = (string) ($profileData[self::KEY_ROLE_ARN] ?? '');
        $roleSessionName = (string) ($profileData[self::KEY_ROLE_SESSION_NAME] ?? \uniqid('async-aws-', true));
        if (null === $sourceProfileName = $profileData[self::KEY_SOURCE_PROFILE] ?? null) {
            $this->logger->warning('The source profile is not defined in Role "{profile}".', ['profile' => $profile]);

            return null;
        }

        /** @var string $sourceProfileName */
        $sourceCredentials = $this->getCredentialsFromProfile($profilesData, $sourceProfileName, $circularCollector);
        if (null === $sourceCredentials) {
            $this->logger->warning('The source profile "{profile}" does not contains valid credentials.', ['profile' => $profile]);

            return null;
        }

        $stsClient = new StsClient(isset($profilesData[$sourceProfileName]['region']) ? ['region' => $profilesData[$sourceProfileName]['region']] : [], $sourceCredentials);

        $result = $stsClient->assumeRole([
            'RoleArn' => $roleArn,
            'RoleSessionName' => $roleSessionName,
        ]);

        try {
            if (null === $credentials = $result->getCredentials()) {
                throw new \RuntimeException('The AsumeRole response does not contains credentials');
            }
        } catch (\Exception $e) {
            $this->logger->warning('Failed to get credentials from assumed role in profile "{profile}: {exception}".', ['profile' => $profile, 'exception' => $e]);

            return null;
        }

        return new Credentials(
            $credentials->getAccessKeyId(),
            $credentials->getSecretAccessKey(),
            $credentials->getSessionToken(),
            $credentials->getExpiration()
        );
    }

    private function getHomeDir(): string
    {
        // On Linux/Unix-like systems, use the HOME environment variable
        if (false !== $homeDir = \getenv('HOME')) {
            return $homeDir;
        }

        // Get the HOMEDRIVE and HOMEPATH values for Windows hosts
        $homeDrive = \getenv('HOMEDRIVE');
        $homePath = \getenv('HOMEPATH');

        return ($homeDrive && $homePath) ? $homeDrive . $homePath : '/';
    }

    private function loadProfiles(array $filepaths): array
    {
        $profilesData = [];
        $homeDir = null;
        foreach ($filepaths as $filepath) {
            if ('' === $filepath) {
                continue;
            }
            if ('~' === $filepath[0]) {
                $homeDir = $homeDir ?? $this->getHomeDir();
                $filepath = $homeDir . \substr($filepath, 1);
            }
            if (!\is_readable($filepath)) {
                continue;
            }

            foreach ($this->parseIniFile($filepath) as $name => $profile) {
                $name = \preg_replace('/^profile /', '', $name);
                if (!isset($profilesData[$name])) {
                    $profilesData[$name] = \array_map('trim', $profile);
                }
            }
        }

        return $profilesData;
    }

    private function parseIniFile(string $filepath): array
    {
        if (false === $data = \parse_ini_string(
            \preg_replace('/^#/m', ';', \file_get_contents($filepath)),
            true,
            \INI_SCANNER_RAW
        )) {
            $this->logger->warning('The ini file {path} is invalid.', ['path' => $filepath]);

            return [];
        }

        return $data;
    }
}
