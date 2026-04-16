<?php

namespace AsyncAws\Ec2\Enum;

final class DeviceType
{
    public const EBS = 'ebs';
    public const INSTANCE_STORE = 'instance-store';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EBS => true,
            self::INSTANCE_STORE => true,
        ][$value]);
    }
}
