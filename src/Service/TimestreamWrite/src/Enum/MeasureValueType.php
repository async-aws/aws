<?php

namespace AsyncAws\TimestreamWrite\Enum;

/**
 * Contains the data type of the MeasureValue for the time-series data point.
 */
final class MeasureValueType
{
    public const BIGINT = 'BIGINT';
    public const BOOLEAN = 'BOOLEAN';
    public const DOUBLE = 'DOUBLE';
    public const MULTI = 'MULTI';
    public const TIMESTAMP = 'TIMESTAMP';
    public const VARCHAR = 'VARCHAR';

    public static function exists(string $value): bool
    {
        return isset([
            self::BIGINT => true,
            self::BOOLEAN => true,
            self::DOUBLE => true,
            self::MULTI => true,
            self::TIMESTAMP => true,
            self::VARCHAR => true,
        ][$value]);
    }
}
