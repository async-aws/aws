<?php

namespace AsyncAws\Translate\Enum;

final class Formality
{
    public const FORMAL = 'FORMAL';
    public const INFORMAL = 'INFORMAL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FORMAL => true,
            self::INFORMAL => true,
        ][$value]);
    }
}
