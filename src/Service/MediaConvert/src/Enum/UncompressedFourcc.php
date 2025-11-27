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
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::I420 => true,
            self::I422 => true,
            self::I444 => true,
        ][$value]);
    }
}
