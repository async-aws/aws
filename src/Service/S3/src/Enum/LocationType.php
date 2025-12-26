<?php

namespace AsyncAws\S3\Enum;

final class LocationType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const AVAILABILITY_ZONE = 'AvailabilityZone';
    public const LOCAL_ZONE = 'LocalZone';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AVAILABILITY_ZONE => true,
            self::LOCAL_ZONE => true,
        ][$value]);
    }
}
