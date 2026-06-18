<?php

namespace AsyncAws\BedrockAgent\Enum;

/**
 * The type of principal in an access control entry.
 */
final class AccessControlPrincipalType
{
    public const USER = 'USER';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::USER => true,
        ][$value]);
    }
}
