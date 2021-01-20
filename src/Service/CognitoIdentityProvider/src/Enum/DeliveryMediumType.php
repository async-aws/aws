<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

/**
 * The delivery medium to send the MFA code. You can use this parameter to set only the `SMS` delivery medium value.
 */
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
