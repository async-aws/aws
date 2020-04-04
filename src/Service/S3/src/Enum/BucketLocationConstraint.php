<?php

namespace AsyncAws\S3\Enum;

final class BucketLocationConstraint
{
    public const AP_NORTHEAST_1 = 'ap-northeast-1';
    public const AP_SOUTHEAST_1 = 'ap-southeast-1';
    public const AP_SOUTHEAST_2 = 'ap-southeast-2';
    public const AP_SOUTH_1 = 'ap-south-1';
    public const CN_NORTH_1 = 'cn-north-1';
    public const EU = 'EU';
    public const EU_CENTRAL_1 = 'eu-central-1';
    public const EU_WEST_1 = 'eu-west-1';
    public const SA_EAST_1 = 'sa-east-1';
    public const US_WEST_1 = 'us-west-1';
    public const US_WEST_2 = 'us-west-2';

    public static function exists(string $value): bool
    {
        return isset([
            self::AP_NORTHEAST_1 => true,
            self::AP_SOUTHEAST_1 => true,
            self::AP_SOUTHEAST_2 => true,
            self::AP_SOUTH_1 => true,
            self::CN_NORTH_1 => true,
            self::EU => true,
            self::EU_CENTRAL_1 => true,
            self::EU_WEST_1 => true,
            self::SA_EAST_1 => true,
            self::US_WEST_1 => true,
            self::US_WEST_2 => true,
        ][$value]);
    }
}
