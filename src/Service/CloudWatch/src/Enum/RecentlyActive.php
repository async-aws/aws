<?php

namespace AsyncAws\CloudWatch\Enum;

final class RecentlyActive
{
    public const PT3H = 'PT3H';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PT3H => true,
        ][$value]);
    }
}
