<?php

namespace AsyncAws\DynamoDb\Enum;

final class GlobalTableSettingsReplicationMode
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const ENABLED_WITH_OVERRIDES = 'ENABLED_WITH_OVERRIDES';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
            self::ENABLED_WITH_OVERRIDES => true,
        ][$value]);
    }
}
