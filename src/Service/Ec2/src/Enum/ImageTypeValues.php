<?php

namespace AsyncAws\Ec2\Enum;

final class ImageTypeValues
{
    public const KERNEL = 'kernel';
    public const MACHINE = 'machine';
    public const RAMDISK = 'ramdisk';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::KERNEL => true,
            self::MACHINE => true,
            self::RAMDISK => true,
        ][$value]);
    }
}
