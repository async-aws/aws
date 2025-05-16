<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether this set of input captions appears in your outputs in both STL and Teletext format. If you choose
 * Upconvert, MediaConvert includes the captions data in two ways: it passes the STL data through using the Teletext
 * compatibility bytes fields of the Teletext wrapper, and it also translates the STL data into Teletext.
 */
final class CaptionSourceUpconvertSTLToTeletext
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
