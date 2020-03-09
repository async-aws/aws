<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Helper object that holds all configuration to the API.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Configuration
{
    public const OPTION_REGION = 'region';
    public const OPTION_PROFILE = 'profile';
    public const OPTION_ACCESS_KEY_ID = 'accessKeyId';
    public const OPTION_SECRET_ACCESS_KEY = 'accessKeySecret';
    public const OPTION_SESSION_TOKEN = 'sessionToken';
    public const OPTION_SHARED_CREDENTIALS_FILE = 'sharedCredentialsFile';
    public const OPTION_SHARED_CONFIG_FILE = 'sharedConfigFile';
    public const OPTION_ENDPOINT = 'endpoint';
    public const OPTION_ROLE_ARN = 'roleArn';
    public const OPTION_WEB_IDENTITY_TOKEN_FILE = 'webIdentityTokenFile';
    public const OPTION_ROLE_SESSION_NAME = 'roleSessionName';

    private const AVAILABLE_OPTIONS = [
        self::OPTION_REGION => true,
        self::OPTION_PROFILE => true,
        self::OPTION_ACCESS_KEY_ID => true,
        self::OPTION_SECRET_ACCESS_KEY => true,
        self::OPTION_SESSION_TOKEN => true,
        self::OPTION_SHARED_CREDENTIALS_FILE => true,
        self::OPTION_SHARED_CONFIG_FILE => true,
        self::OPTION_ENDPOINT => true,
        self::OPTION_ROLE_ARN => true,
        self::OPTION_WEB_IDENTITY_TOKEN_FILE => true,
        self::OPTION_ROLE_SESSION_NAME => true,
    ];

    // Put fallback options into groups to avoid mixing of provided config and environment variables
    private const FALLBACK_OPTIONS = [
        [self::OPTION_REGION => ['AWS_REGION', 'AWS_DEFAULT_REGION']],
        [self::OPTION_PROFILE => ['AWS_PROFILE', 'AWS_DEFAULT_PROFILE']],
        [
            self::OPTION_ACCESS_KEY_ID => ['AWS_ACCESS_KEY_ID', 'AWS_ACCESS_KEY'],
            self::OPTION_SECRET_ACCESS_KEY => ['AWS_SECRET_ACCESS_KEY', 'AWS_SECRET_KEY'],
            self::OPTION_SESSION_TOKEN => 'AWS_SESSION_TOKEN',
        ],
        [self::OPTION_SHARED_CREDENTIALS_FILE => 'AWS_SHARED_CREDENTIALS_FILE'],
        [self::OPTION_SHARED_CONFIG_FILE => 'AWS_CONFIG_FILE'],
        [
            self::OPTION_ROLE_ARN => 'AWS_ROLE_ARN',
            self::OPTION_WEB_IDENTITY_TOKEN_FILE => 'AWS_WEB_IDENTITY_TOKEN_FILE',
            self::OPTION_ROLE_SESSION_NAME => 'AWS_ROLE_SESSION_NAME',
        ],
    ];

    private const DEFAULT_OPTIONS = [
        self::OPTION_REGION => 'us-east-1',
        self::OPTION_PROFILE => 'default',
        self::OPTION_SHARED_CREDENTIALS_FILE => '~/.aws/credentials',
        self::OPTION_SHARED_CONFIG_FILE => '~/.aws/config',
        // https://docs.aws.amazon.com/general/latest/gr/rande.html
        self::OPTION_ENDPOINT => 'https://%service%.%region%.amazonaws.com',
    ];

    private $data = [];

    public static function create(array $options)
    {
        if (0 < \count($invalidOptions = \array_diff_key($options, self::AVAILABLE_OPTIONS))) {
            throw new InvalidArgument(\sprintf('Invalid option(s) "%s" passed to "%s::%s". ', \implode('", "', \array_keys($invalidOptions)), __CLASS__, __METHOD__));
        }

        foreach (self::FALLBACK_OPTIONS as $fallbackGroup) {
            // prevent mixing env variables with config keys
            foreach ($fallbackGroup as $option => $envVariableNames) {
                if (isset($options[$option])) {
                    continue 2;
                }
            }

            foreach ($fallbackGroup as $option => $envVariableNames) {
                $envVariableNames = (array) $envVariableNames;
                foreach ($envVariableNames as $envVariableName) {
                    if (false !== $value = \getenv($envVariableName)) {
                        $options[$option] = $value;

                        break;
                    }
                }
            }
        }

        foreach (self::DEFAULT_OPTIONS as $optionTrigger => $defaultValue) {
            if (isset($options[$optionTrigger])) {
                continue;
            }

            $options[$optionTrigger] = $defaultValue;
        }

        $configuration = new Configuration();
        $configuration->data = $options;

        return $configuration;
    }

    public function get(string $name): ?string
    {
        if (!isset(self::AVAILABLE_OPTIONS[$name])) {
            throw new InvalidArgument(\sprintf('Invalid option "%s" passed to "%s::%s". ', $name, __CLASS__, __METHOD__));
        }

        return $this->data[$name] ?? null;
    }

    public function has(string $name): bool
    {
        if (!isset(self::AVAILABLE_OPTIONS[$name])) {
            throw new InvalidArgument(\sprintf('Invalid option "%s" passed to "%s::%s". ', $name, __CLASS__, __METHOD__));
        }

        return isset($this->data[$name]);
    }

    public function isDefault(string $name): bool
    {
        if (!isset(self::AVAILABLE_OPTIONS[$name])) {
            throw new InvalidArgument(\sprintf('Invalid option "%s" passed to "%s::%s". ', $name, __CLASS__, __METHOD__));
        }

        return isset($this->data[$name], self::DEFAULT_OPTIONS[$name]) && $this->data[$name] === self::DEFAULT_OPTIONS[$name];
    }
}
