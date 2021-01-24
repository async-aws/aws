<?php

namespace AsyncAws\DynamoDb\Enum;

/**
 * Represents the current state of server-side encryption. The only supported values are:.
 *
 * - `ENABLED` - Server-side encryption is enabled.
 * - `UPDATING` - Server-side encryption is being updated.
 */
final class SSEStatus
{
    public const DISABLED = 'DISABLED';
    public const DISABLING = 'DISABLING';
    public const ENABLED = 'ENABLED';
    public const ENABLING = 'ENABLING';
    public const UPDATING = 'UPDATING';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::DISABLING => true,
            self::ENABLED => true,
            self::ENABLING => true,
            self::UPDATING => true,
        ][$value]);
    }
}
