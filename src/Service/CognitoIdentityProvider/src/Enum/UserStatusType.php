<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

final class UserStatusType
{
    public const ARCHIVED = 'ARCHIVED';
    public const COMPROMISED = 'COMPROMISED';
    public const CONFIRMED = 'CONFIRMED';
    public const EXTERNAL_PROVIDER = 'EXTERNAL_PROVIDER';
    public const FORCE_CHANGE_PASSWORD = 'FORCE_CHANGE_PASSWORD';
    public const RESET_REQUIRED = 'RESET_REQUIRED';
    public const UNCONFIRMED = 'UNCONFIRMED';
    public const UNKNOWN = 'UNKNOWN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ARCHIVED => true,
            self::COMPROMISED => true,
            self::CONFIRMED => true,
            self::EXTERNAL_PROVIDER => true,
            self::FORCE_CHANGE_PASSWORD => true,
            self::RESET_REQUIRED => true,
            self::UNCONFIRMED => true,
            self::UNKNOWN => true,
        ][$value]);
    }
}
