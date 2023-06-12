<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

final class UserStatusType
{
    public const ARCHIVED = 'ARCHIVED';
    public const COMPROMISED = 'COMPROMISED';
    public const CONFIRMED = 'CONFIRMED';
    public const FORCE_CHANGE_PASSWORD = 'FORCE_CHANGE_PASSWORD';
    public const RESET_REQUIRED = 'RESET_REQUIRED';
    public const UNCONFIRMED = 'UNCONFIRMED';
    public const UNKNOWN = 'UNKNOWN';

    public static function exists(string $value): bool
    {
        return isset([
            self::ARCHIVED => true,
            self::COMPROMISED => true,
            self::CONFIRMED => true,
            self::FORCE_CHANGE_PASSWORD => true,
            self::RESET_REQUIRED => true,
            self::UNCONFIRMED => true,
            self::UNKNOWN => true,
        ][$value]);
    }
}
