<?php

namespace AsyncAws\CodeDeploy\Enum;

final class FileExistsBehavior
{
    public const DISALLOW = 'DISALLOW';
    public const OVERWRITE = 'OVERWRITE';
    public const RETAIN = 'RETAIN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISALLOW => true,
            self::OVERWRITE => true,
            self::RETAIN => true,
        ][$value]);
    }
}
