<?php

namespace AsyncAws\Translate\Enum;

final class Brevity
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ON = 'ON';

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
