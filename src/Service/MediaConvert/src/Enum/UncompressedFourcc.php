<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The four character code for the uncompressed video.
 */
final class UncompressedFourcc
{
    public const I420 = 'I420';
    public const I422 = 'I422';
    public const I444 = 'I444';

    public static function exists(string $value): bool
    {
        return isset([
            self::I420 => true,
            self::I422 => true,
            self::I444 => true,
        ][$value]);
    }
}
