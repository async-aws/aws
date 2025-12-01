<?php

namespace AsyncAws\CodeCommit\Enum;

final class ChangeTypeEnum
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const A = 'A';
    public const D = 'D';
    public const M = 'M';

    public static function exists(string $value): bool
    {
        return isset([
            self::A => true,
            self::D => true,
            self::M => true,
        ][$value]);
    }
}
