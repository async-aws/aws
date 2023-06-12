<?php

namespace AsyncAws\Lambda\Enum;

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
