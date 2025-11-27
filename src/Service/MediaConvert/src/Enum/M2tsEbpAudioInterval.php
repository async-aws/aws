<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to VIDEO_AND_FIXED_INTERVALS, audio EBP markers will be added to partitions 3 and 4. The interval between
 * these additional markers will be fixed, and will be slightly shorter than the video EBP marker interval. When set to
 * VIDEO_INTERVAL, these additional markers will not be inserted. Only applicable when EBP segmentation markers are is
 * selected (segmentationMarkers is EBP or EBP_LEGACY).
 */
final class M2tsEbpAudioInterval
{
    public const VIDEO_AND_FIXED_INTERVALS = 'VIDEO_AND_FIXED_INTERVALS';
    public const VIDEO_INTERVAL = 'VIDEO_INTERVAL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::VIDEO_AND_FIXED_INTERVALS => true,
            self::VIDEO_INTERVAL => true,
        ][$value]);
    }
}
