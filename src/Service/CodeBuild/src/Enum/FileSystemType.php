<?php

namespace AsyncAws\CodeBuild\Enum;

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
