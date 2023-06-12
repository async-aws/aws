<?php

namespace AsyncAws\TimestreamWrite\Enum;

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
