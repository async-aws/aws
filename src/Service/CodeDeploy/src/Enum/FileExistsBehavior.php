<?php

namespace AsyncAws\CodeDeploy\Enum;

final class FileExistsBehavior
{
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
