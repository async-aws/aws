<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

final class AuthFlowType
{
    public const ADMIN_NO_SRP_AUTH = 'ADMIN_NO_SRP_AUTH';
    public const ADMIN_USER_PASSWORD_AUTH = 'ADMIN_USER_PASSWORD_AUTH';
    public const CUSTOM_AUTH = 'CUSTOM_AUTH';
    public const REFRESH_TOKEN = 'REFRESH_TOKEN';
    public const REFRESH_TOKEN_AUTH = 'REFRESH_TOKEN_AUTH';
    public const USER_AUTH = 'USER_AUTH';
    public const USER_PASSWORD_AUTH = 'USER_PASSWORD_AUTH';
    public const USER_SRP_AUTH = 'USER_SRP_AUTH';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ADMIN_NO_SRP_AUTH => true,
            self::ADMIN_USER_PASSWORD_AUTH => true,
            self::CUSTOM_AUTH => true,
            self::REFRESH_TOKEN => true,
            self::REFRESH_TOKEN_AUTH => true,
            self::USER_AUTH => true,
            self::USER_PASSWORD_AUTH => true,
            self::USER_SRP_AUTH => true,
        ][$value]);
    }
}
