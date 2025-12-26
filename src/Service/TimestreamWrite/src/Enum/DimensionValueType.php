<?php

namespace AsyncAws\TimestreamWrite\Enum;

final class DimensionValueType
{
    public const VARCHAR = 'VARCHAR';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::VARCHAR => true,
        ][$value]);
    }
}
