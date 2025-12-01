<?php

namespace AsyncAws\CodeBuild\Enum;

final class FileSystemType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const EFS = 'EFS';

    public static function exists(string $value): bool
    {
        return isset([
            self::EFS => true,
        ][$value]);
    }
}
