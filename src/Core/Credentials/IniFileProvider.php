<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\InvalidArgument;
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
            $configuration->get(Configuration::OPTION_SHARED_CONFIG_FILE)
        ]);
        if (empty($profilesData)) {
            return null;
        }

        /** @var string $profile */
        $profile = $configuration->get(Configuration::OPTION_PROFILE);
        if (!isset($profilesData[$profile])) {
            $this->logger->info('Profile {profile} not found.', ['profile' => $profile]);

            return null;
        }

        $profileData = $profilesData[$profile];
        if (isset($profileData[self::KEY_ACCESS_KEY_ID], $profileData[self::KEY_ACCESS_KEY_ID])) {
            return new Credentials(
                $profileData[self::KEY_ACCESS_KEY_ID],
                $profileData[self::KEY_SECRET_ACCESS_KEY],
                $profileData[self::KEY_SESSION_TOKEN] ?? null
            );
        }

        if (isset($profileData[self::KEY_ROLE_ARN])) {
            throw new InvalidArgument('Assume role is not implemented Yet.');
        }

        $this->logger->info('No credentials found for profile {profile}.', ['profile' => $profile]);

        return null;
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
        foreach ($filepaths as $filepath) {
            if ('' === $filepath) {
                continue;
            }
            if ('~' === $filepath[0]) {
                // FIXME is $homeDir ever defined?
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
