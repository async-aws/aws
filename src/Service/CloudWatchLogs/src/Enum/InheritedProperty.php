<?php

namespace AsyncAws\CloudWatchLogs\Enum;

final class InheritedProperty
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ACCOUNT_DATA_PROTECTION = 'ACCOUNT_DATA_PROTECTION';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACCOUNT_DATA_PROTECTION => true,
        ][$value]);
    }
}
