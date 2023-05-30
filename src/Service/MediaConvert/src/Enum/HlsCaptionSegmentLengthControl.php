<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set Caption segment length control (CaptionSegmentLengthControl) to Match video (MATCH_VIDEO) to create caption
 * segments that align with the video segments from the first video output in this output group. For example, if the
 * video segments are 2 seconds long, your WebVTT segments will also be 2 seconds long. Keep the default setting, Large
 * segments (LARGE_SEGMENTS) to create caption segments that are 300 seconds long.
 */
final class HlsCaptionSegmentLengthControl
{
    public const LARGE_SEGMENTS = 'LARGE_SEGMENTS';
    public const MATCH_VIDEO = 'MATCH_VIDEO';

    public static function exists(string $value): bool
    {
        return isset([
            self::LARGE_SEGMENTS => true,
            self::MATCH_VIDEO => true,
        ][$value]);
    }
}
