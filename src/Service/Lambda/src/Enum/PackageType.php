<?php

namespace AsyncAws\Lambda\Enum;

final class PackageType
{
    public const IMAGE = 'Image';
    public const ZIP = 'Zip';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::IMAGE => true,
            self::ZIP => true,
        ][$value]);
    }
}
