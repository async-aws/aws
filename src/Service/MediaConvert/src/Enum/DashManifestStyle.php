<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how MediaConvert writes SegmentTimeline in your output DASH manifest. To write a SegmentTimeline in each
 * video Representation: Keep the default value, Basic. To write a common SegmentTimeline in the video AdaptationSet:
 * Choose Compact. Note that MediaConvert will still write a SegmentTimeline in any Representation that does not share a
 * common timeline. To write a video AdaptationSet for each different output framerate, and a common SegmentTimeline in
 * each AdaptationSet: Choose Distinct.
 */
final class DashManifestStyle
{
    public const BASIC = 'BASIC';
    public const COMPACT = 'COMPACT';
    public const DISTINCT = 'DISTINCT';

    public static function exists(string $value): bool
    {
        return isset([
            self::BASIC => true,
            self::COMPACT => true,
            self::DISTINCT => true,
        ][$value]);
    }
}
