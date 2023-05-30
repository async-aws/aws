<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable use of tiles, allowing horizontal as well as vertical subdivision of the encoded pictures.
 */
final class H265Tiles
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
