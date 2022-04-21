<?php

namespace AsyncAws\CodeCommit\Enum;

/**
 * Whether the change type of the difference is an addition (A), deletion (D), or modification (M).
 */
final class ChangeTypeEnum
{
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
