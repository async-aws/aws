<?php

namespace AsyncAws\Lambda\Enum;

final class PackageType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const IMAGE = 'Image';
    public const ZIP = 'Zip';

    public static function exists(string $value): bool
    {
        return isset([
            self::IMAGE => true,
            self::ZIP => true,
        ][$value]);
    }
}
