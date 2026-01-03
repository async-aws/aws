<?php

namespace AsyncAws\Translate\Enum;

final class Brevity
{
    public const ON = 'ON';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ON => true,
        ][$value]);
    }
}
