<?php

namespace AsyncAws\ImageBuilder\Enum;

final class DiskImageFormat
{
    public const RAW = 'RAW';
    public const VHD = 'VHD';
    public const VMDK = 'VMDK';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::RAW => true,
            self::VHD => true,
            self::VMDK => true,
        ][$value]);
    }
}
