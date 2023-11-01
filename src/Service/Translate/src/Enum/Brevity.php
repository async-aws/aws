<?php

namespace AsyncAws\Translate\Enum;

final class Brevity
{
    public const ON = 'ON';

    public static function exists(string $value): bool
    {
        return isset([
            self::ON => true,
        ][$value]);
    }
}
