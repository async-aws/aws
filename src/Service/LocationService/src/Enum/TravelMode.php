<?php

namespace AsyncAws\LocationService\Enum;

final class TravelMode
{
    public const BICYCLE = 'Bicycle';
    public const CAR = 'Car';
    public const MOTORCYCLE = 'Motorcycle';
    public const TRUCK = 'Truck';
    public const WALKING = 'Walking';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BICYCLE => true,
            self::CAR => true,
            self::MOTORCYCLE => true,
            self::TRUCK => true,
            self::WALKING => true,
        ][$value]);
    }
}
