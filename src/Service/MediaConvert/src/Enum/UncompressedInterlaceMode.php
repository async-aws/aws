<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Choose the scan line type for this output. If you don't specify a value, MediaConvert will create a
 * progressive output.
 */
final class UncompressedInterlaceMode
{
    public const INTERLACED = 'INTERLACED';
    public const PROGRESSIVE = 'PROGRESSIVE';

    public static function exists(string $value): bool
    {
        return isset([
            self::INTERLACED => true,
            self::PROGRESSIVE => true,
        ][$value]);
    }
}
