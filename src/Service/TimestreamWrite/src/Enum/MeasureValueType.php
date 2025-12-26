<?php

namespace AsyncAws\TimestreamWrite\Enum;

final class MeasureValueType
{
    public const BIGINT = 'BIGINT';
    public const BOOLEAN = 'BOOLEAN';
    public const DOUBLE = 'DOUBLE';
    public const MULTI = 'MULTI';
    public const TIMESTAMP = 'TIMESTAMP';
    public const VARCHAR = 'VARCHAR';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
