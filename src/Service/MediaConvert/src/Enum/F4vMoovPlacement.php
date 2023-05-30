<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If set to PROGRESSIVE_DOWNLOAD, the MOOV atom is relocated to the beginning of the archive as required for
 * progressive downloading. Otherwise it is placed normally at the end.
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
