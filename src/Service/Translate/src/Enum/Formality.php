<?php

namespace AsyncAws\Translate\Enum;

final class Formality
{
    public const FORMAL = 'FORMAL';
    public const INFORMAL = 'INFORMAL';

    public static function exists(string $value): bool
    {
        return isset([
            self::FORMAL => true,
            self::INFORMAL => true,
        ][$value]);
    }
}
