<?php

namespace AsyncAws\CodeDeploy\Enum;

final class FileExistsBehavior
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const DISALLOW = 'DISALLOW';
    public const OVERWRITE = 'OVERWRITE';
    public const RETAIN = 'RETAIN';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISALLOW => true,
            self::OVERWRITE => true,
            self::RETAIN => true,
        ][$value]);
    }
}
