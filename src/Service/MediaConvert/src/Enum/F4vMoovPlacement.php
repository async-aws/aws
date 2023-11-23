<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * To place the MOOV atom at the beginning of your output, which is useful for progressive downloading: Leave blank or
 * choose Progressive download. To place the MOOV at the end of your output: Choose Normal.
 */
final class F4vMoovPlacement
{
    public const NORMAL = 'NORMAL';
    public const PROGRESSIVE_DOWNLOAD = 'PROGRESSIVE_DOWNLOAD';

    public static function exists(string $value): bool
    {
        return isset([
            self::NORMAL => true,
            self::PROGRESSIVE_DOWNLOAD => true,
        ][$value]);
    }
}
