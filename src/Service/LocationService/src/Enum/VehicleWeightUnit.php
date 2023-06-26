<?php

namespace AsyncAws\LocationService\Enum;

final class VehicleWeightUnit
{
    public const KILOGRAMS = 'Kilograms';
    public const POUNDS = 'Pounds';

    public static function exists(string $value): bool
    {
        return isset([
            self::KILOGRAMS => true,
            self::POUNDS => true,
        ][$value]);
    }
}
