<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set to "padded" to force MediaConvert to add padding to the frame, to obtain a frame that is a whole multiple of the
 * tile size. If you are setting up the picture as a tile, you must enter "padded". In all other configurations, you
 * typically enter "none".
 */
final class H265TilePadding
{
    public const NONE = 'NONE';
    public const PADDED = 'PADDED';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PADDED => true,
        ][$value]);
    }
}
