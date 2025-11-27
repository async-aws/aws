<?php

namespace AsyncAws\CodeBuild\Enum;

final class CredentialProviderType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const SECRETS_MANAGER = 'SECRETS_MANAGER';

    public static function exists(string $value): bool
    {
        return isset([
            self::SECRETS_MANAGER => true,
        ][$value]);
    }
}
