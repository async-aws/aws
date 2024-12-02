<?php

namespace AsyncAws\S3\Enum;

final class LocationType
{
    public const AVAILABILITY_ZONE = 'AvailabilityZone';
    public const LOCAL_ZONE = 'LocalZone';

    public static function exists(string $value): bool
    {
        return isset([
            self::AVAILABILITY_ZONE => true,
            self::LOCAL_ZONE => true,
        ][$value]);
    }
}
