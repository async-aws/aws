<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how MediaConvert writes SegmentTimeline in your output DASH manifest. To write a SegmentTimeline for outputs
 * that you also specify a Name modifier for: Keep the default value, Basic. Note that if you do not specify a name
 * modifier for an output, MediaConvert will not write a SegmentTimeline for it. To write a common SegmentTimeline in
 * the video AdaptationSet: Choose Compact. Note that MediaConvert will still write a SegmentTimeline in any
 * Representation that does not share a common timeline. To write a video AdaptationSet for each different output
 * framerate, and a common SegmentTimeline in each AdaptationSet: Choose Distinct. To write a SegmentTimeline in each
 * AdaptationSet: Choose Full.
 */
final class DashManifestStyle
{
    public const BASIC = 'BASIC';
    public const COMPACT = 'COMPACT';
    public const DISTINCT = 'DISTINCT';
    public const FULL = 'FULL';

    public static function exists(string $value): bool
    {
        return isset([
            self::BASIC => true,
            self::COMPACT => true,
            self::DISTINCT => true,
            self::FULL => true,
        ][$value]);
    }
}
