<?php

namespace AsyncAws\Lambda\Enum;

/**
 * Set to `ALL` to include entries for all published versions of each function.
 */
final class FunctionVersion
{
    public const ALL = 'ALL';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
        ][$value]);
    }
}
