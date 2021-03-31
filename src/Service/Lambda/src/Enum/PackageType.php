<?php

namespace AsyncAws\Lambda\Enum;

/**
 * The type of deployment package. Set to `Image` for container image and set `Zip` for .zip file archive.
 */
final class PackageType
{
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
