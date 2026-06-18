<?php

namespace AsyncAws\BedrockAgent\Enum;

/**
 * The access level for an access control entry.
 */
final class AccessControlAccess
{
    public const ALLOW = 'ALLOW';
    public const DENY = 'DENY';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALLOW => true,
            self::DENY => true,
        ][$value]);
    }
}
