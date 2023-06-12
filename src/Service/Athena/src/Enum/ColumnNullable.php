<?php

namespace AsyncAws\Athena\Enum;

final class ColumnNullable
{
    public const NOT_NULL = 'NOT_NULL';
    public const NULLABLE = 'NULLABLE';
    public const UNKNOWN = 'UNKNOWN';

    public static function exists(string $value): bool
    {
        return isset([
            self::NOT_NULL => true,
            self::NULLABLE => true,
            self::UNKNOWN => true,
        ][$value]);
    }
}
