<?php

namespace AsyncAws\DynamoDb\Enum;

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
