<?php

namespace AsyncAws\Translate\Enum;

final class Profanity
{
    public const MASK = 'MASK';

    public static function exists(string $value): bool
    {
        return isset([
            self::MASK => true,
        ][$value]);
    }
}
