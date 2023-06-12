<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the Bit depth (Av1BitDepth). You can choose 8-bit (BIT_8) or 10-bit (BIT_10).
 */
final class Av1BitDepth
{
    public const BIT_10 = 'BIT_10';
    public const BIT_8 = 'BIT_8';

    public static function exists(string $value): bool
    {
        return isset([
            self::BIT_10 => true,
            self::BIT_8 => true,
        ][$value]);
    }
}
