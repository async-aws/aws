<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Unless you need Omneon compatibility: Keep the default value, None. To make this output compatible with Omneon:
 * Choose Omneon. When you do, MediaConvert increases the length of the 'elst' edit list atom. Note that this might
 * cause file rejections when a recipient of the output file doesn't expect this extra padding.
 */
final class MovPaddingControl
{
    public const NONE = 'NONE';
    public const OMNEON = 'OMNEON';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::OMNEON => true,
        ][$value]);
    }
}
