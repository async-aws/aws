<?php

namespace AsyncAws\Ec2\Enum;

final class BootModeValues
{
    public const LEGACY_BIOS = 'legacy-bios';
    public const UEFI = 'uefi';
    public const UEFI_PREFERRED = 'uefi-preferred';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LEGACY_BIOS => true,
            self::UEFI => true,
            self::UEFI_PREFERRED => true,
        ][$value]);
    }
}
