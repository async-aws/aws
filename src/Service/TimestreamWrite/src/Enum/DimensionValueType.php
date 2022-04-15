<?php

namespace AsyncAws\TimestreamWrite\Enum;

/**
 * The data type of the dimension for the time series data point.
 */
final class DimensionValueType
{
    public const VARCHAR = 'VARCHAR';

    public static function exists(string $value): bool
    {
        return isset([
            self::VARCHAR => true,
        ][$value]);
    }
}
