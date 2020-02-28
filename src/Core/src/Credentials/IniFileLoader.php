<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Load and parse AWS iniFile.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class IniFileLoader
{
    public const KEY_REGION = 'region';
    public const KEY_ACCESS_KEY_ID = 'aws_access_key_id';
    public const KEY_SECRET_ACCESS_KEY = 'aws_secret_access_key';
    public const KEY_SESSION_TOKEN = 'aws_session_token';
    public const KEY_ROLE_ARN = 'role_arn';
    public const KEY_ROLE_SESSION_NAME = 'role_session_name';
    public const KEY_SOURCE_PROFILE = 'source_profile';
    public const KEY_WEB_IDENTITY_TOKEN_FILE = 'web_identity_token_file';

    private $logger;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param string[] $filepaths
     *
     * @return string[][]
     */
    public function loadProfiles(array $filepaths): array
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
