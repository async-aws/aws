<?php

namespace AsyncAws\CodeBuild\Enum;

final class CredentialProviderType
{
    public const SECRETS_MANAGER = 'SECRETS_MANAGER';

    public static function exists(string $value): bool
    {
        return isset([
            self::SECRETS_MANAGER => true,
        ][$value]);
    }
}
