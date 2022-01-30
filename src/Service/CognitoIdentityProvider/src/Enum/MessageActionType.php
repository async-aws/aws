<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

/**
 * Set to `RESEND` to resend the invitation message to a user that already exists and reset the expiration limit on the
 * user's account. Set to `SUPPRESS` to suppress sending the message. You can specify only one value.
 */
final class MessageActionType
{
    public const RESEND = 'RESEND';
    public const SUPPRESS = 'SUPPRESS';

    public static function exists(string $value): bool
    {
        return isset([
            self::RESEND => true,
            self::SUPPRESS => true,
        ][$value]);
    }
}
