<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the video sample composition time offset mode in the output fMP4 TRUN box. For wider player compatibility,
 * set Video composition offsets to Unsigned or leave blank. The earliest presentation time may be greater than zero,
 * and sample composition time offsets will increment using unsigned integers. For strict fMP4 video and audio timing,
 * set Video composition offsets to Signed. The earliest presentation time will be equal to zero, and sample composition
 * time offsets will increment using signed integers.
 */
final class DashIsoVideoCompositionOffsets
{
    public const SIGNED = 'SIGNED';
    public const UNSIGNED = 'UNSIGNED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::SIGNED => true,
            self::UNSIGNED => true,
        ][$value]);
    }
}
