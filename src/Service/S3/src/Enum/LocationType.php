<?php

namespace AsyncAws\S3\Enum;

final class LocationType
{
    public const AVAILABILITY_ZONE = 'AvailabilityZone';

    public static function exists(string $value): bool
    {
        return isset([
            self::AVAILABILITY_ZONE => true,
        ][$value]);
    }
}
