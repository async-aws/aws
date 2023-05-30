<?php

namespace AsyncAws\CodeBuild\Enum;

final class EnvironmentVariableType
{
    public const PARAMETER_STORE = 'PARAMETER_STORE';
    public const PLAINTEXT = 'PLAINTEXT';
    public const SECRETS_MANAGER = 'SECRETS_MANAGER';

    public static function exists(string $value): bool
    {
        return isset([
            self::PARAMETER_STORE => true,
            self::PLAINTEXT => true,
            self::SECRETS_MANAGER => true,
        ][$value]);
    }
}
