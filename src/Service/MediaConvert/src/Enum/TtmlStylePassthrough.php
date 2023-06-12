<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Pass through style and position information from a TTML-like input source (TTML, IMSC, SMPTE-TT) to the TTML output.
 */
final class TtmlStylePassthrough
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
