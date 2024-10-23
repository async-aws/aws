<?php

namespace AsyncAws\TimestreamQuery\Enum;

final class QueryInsightsMode
{
    public const DISABLED = 'DISABLED';
    public const ENABLED_WITH_RATE_CONTROL = 'ENABLED_WITH_RATE_CONTROL';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED_WITH_RATE_CONTROL => true,
        ][$value]);
    }
}
