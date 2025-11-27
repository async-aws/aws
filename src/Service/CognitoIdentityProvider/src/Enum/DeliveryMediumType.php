<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

final class DeliveryMediumType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const EMAIL = 'EMAIL';
    public const SMS = 'SMS';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EMAIL => true,
            self::SMS => true,
        ][$value]);
    }
}
