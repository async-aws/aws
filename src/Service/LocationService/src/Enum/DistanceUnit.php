<?php

namespace AsyncAws\LocationService\Enum;

final class DistanceUnit
{
    public const KILOMETERS = 'Kilometers';
    public const MILES = 'Miles';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::KILOMETERS => true,
            self::MILES => true,
        ][$value]);
    }
}
