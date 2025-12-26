<?php

namespace AsyncAws\LocationService\Enum;

final class DimensionUnit
{
    public const FEET = 'Feet';
    public const METERS = 'Meters';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FEET => true,
            self::METERS => true,
        ][$value]);
    }
}
