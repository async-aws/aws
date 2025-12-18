<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Select the tree block size used for encoding. If you enter "auto", the encoder will pick the best size. If you are
 * setting up the picture as a tile, you must set this to 32x32. In all other configurations, you typically enter
 * "auto".
 */
final class H265TreeBlockSize
{
    public const AUTO = 'AUTO';
    public const TREE_SIZE_32X32 = 'TREE_SIZE_32X32';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::TREE_SIZE_32X32 => true,
        ][$value]);
    }
}
