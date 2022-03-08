<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The type of the file system. The one supported type is `EFS`.
 */
final class FileSystemType
{
    public const EFS = 'EFS';

    public static function exists(string $value): bool
    {
        return isset([
            self::EFS => true,
        ][$value]);
    }
}
