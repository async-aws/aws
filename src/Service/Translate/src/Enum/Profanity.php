<?php

namespace AsyncAws\Translate\Enum;

final class Profanity
{
    public const MASK = 'MASK';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MASK => true,
        ][$value]);
    }
}
