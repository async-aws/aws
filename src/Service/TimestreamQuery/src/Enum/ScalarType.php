<?php

namespace AsyncAws\TimestreamQuery\Enum;

final class ScalarType
{
    public const BIGINT = 'BIGINT';
    public const BOOLEAN = 'BOOLEAN';
    public const DATE = 'DATE';
    public const DOUBLE = 'DOUBLE';
    public const INTEGER = 'INTEGER';
    public const INTERVAL_DAY_TO_SECOND = 'INTERVAL_DAY_TO_SECOND';
    public const INTERVAL_YEAR_TO_MONTH = 'INTERVAL_YEAR_TO_MONTH';
    public const TIME = 'TIME';
    public const TIMESTAMP = 'TIMESTAMP';
    public const UNKNOWN = 'UNKNOWN';
    public const VARCHAR = 'VARCHAR';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BIGINT => true,
            self::BOOLEAN => true,
            self::DATE => true,
            self::DOUBLE => true,
            self::INTEGER => true,
            self::INTERVAL_DAY_TO_SECOND => true,
            self::INTERVAL_YEAR_TO_MONTH => true,
            self::TIME => true,
            self::TIMESTAMP => true,
            self::UNKNOWN => true,
            self::VARCHAR => true,
        ][$value]);
    }
}
