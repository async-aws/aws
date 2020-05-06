<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

final class DeliveryMediumType
{
    public const EMAIL = 'EMAIL';
    public const SMS = 'SMS';

    public static function exists(string $value): bool
    {
        return isset([
            self::EMAIL => true,
            self::SMS => true,
        ][$value]);
    }
}
