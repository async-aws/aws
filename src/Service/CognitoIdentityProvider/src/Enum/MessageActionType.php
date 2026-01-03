<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

final class MessageActionType
{
    public const RESEND = 'RESEND';
    public const SUPPRESS = 'SUPPRESS';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::RESEND => true,
            self::SUPPRESS => true,
        ][$value]);
    }
}
