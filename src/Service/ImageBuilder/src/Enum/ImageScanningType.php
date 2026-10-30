<?php

namespace AsyncAws\ImageBuilder\Enum;

final class ImageScanningType
{
    public const ON_DEMAND_AMI_CVE = 'ON_DEMAND_AMI_CVE';
    public const TEST_INSTANCE_CVE = 'TEST_INSTANCE_CVE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ON_DEMAND_AMI_CVE => true,
            self::TEST_INSTANCE_CVE => true,
        ][$value]);
    }
}
