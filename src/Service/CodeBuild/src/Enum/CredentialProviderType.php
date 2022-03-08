<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The service that created the credentials to access a private Docker registry. The valid value, SECRETS_MANAGER, is
 * for Secrets Manager.
 */
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
