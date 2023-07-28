<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether this set of input captions appears in your outputs in both 608 and 708 format. If you choose
 * Upconvert, MediaConvert includes the captions data in two ways: it passes the 608 data through using the 608
 * compatibility bytes fields of the 708 wrapper, and it also translates the 608 data into 708.
 */
final class EmbeddedConvert608To708
{
    public const DISABLED = 'DISABLED';
    public const UPCONVERT = 'UPCONVERT';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::UPCONVERT => true,
        ][$value]);
    }
}
