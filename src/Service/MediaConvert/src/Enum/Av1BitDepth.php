<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the Bit depth. You can choose 8-bit or 10-bit.
 */
final class Av1BitDepth
{
    public const BIT_10 = 'BIT_10';
    public const BIT_8 = 'BIT_8';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BIT_10 => true,
            self::BIT_8 => true,
        ][$value]);
    }
}
