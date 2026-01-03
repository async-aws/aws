<?php

namespace AsyncAws\Lambda\Enum;

final class FunctionVersion
{
    public const ALL = 'ALL';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
        ][$value]);
    }
}
