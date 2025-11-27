<?php

namespace AsyncAws\DynamoDb\Enum;

final class SSEStatus
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const DISABLED = 'DISABLED';
    public const DISABLING = 'DISABLING';
    public const ENABLED = 'ENABLED';
    public const ENABLING = 'ENABLING';
    public const UPDATING = 'UPDATING';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
