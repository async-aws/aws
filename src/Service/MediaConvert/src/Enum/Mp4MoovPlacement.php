<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * To place the MOOV atom at the beginning of your output, which is useful for progressive downloading: Leave blank or
 * choose Progressive download. To place the MOOV at the end of your output: Choose Normal.
 */
final class Mp4MoovPlacement
{
    public const NORMAL = 'NORMAL';
    public const PROGRESSIVE_DOWNLOAD = 'PROGRESSIVE_DOWNLOAD';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NORMAL => true,
            self::PROGRESSIVE_DOWNLOAD => true,
        ][$value]);
    }
}
