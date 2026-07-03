<?php

namespace AsyncAws\Ses\Enum;

final class IdentityType
{
    public const DOMAIN = 'DOMAIN';
    public const EMAIL_ADDRESS = 'EMAIL_ADDRESS';
    public const MANAGED_DOMAIN = 'MANAGED_DOMAIN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DOMAIN => true,
            self::EMAIL_ADDRESS => true,
            self::MANAGED_DOMAIN => true,
        ][$value]);
    }
}
