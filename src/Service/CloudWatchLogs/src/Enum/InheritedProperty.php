<?php

namespace AsyncAws\CloudWatchLogs\Enum;

final class InheritedProperty
{
    public const ACCOUNT_DATA_PROTECTION = 'ACCOUNT_DATA_PROTECTION';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACCOUNT_DATA_PROTECTION => true,
        ][$value]);
    }
}
