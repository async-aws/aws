<?php

namespace AsyncAws\S3\Enum;

final class DataRedundancy
{
    public const SINGLE_AVAILABILITY_ZONE = 'SingleAvailabilityZone';
    public const SINGLE_LOCAL_ZONE = 'SingleLocalZone';

    public static function exists(string $value): bool
    {
        return isset([
            self::SINGLE_AVAILABILITY_ZONE => true,
            self::SINGLE_LOCAL_ZONE => true,
        ][$value]);
    }
}
