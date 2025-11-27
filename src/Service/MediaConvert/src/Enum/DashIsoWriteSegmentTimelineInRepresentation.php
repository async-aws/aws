<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you enable Precise segment duration in manifests, your DASH manifest shows precise segment durations. The
 * segment duration information appears inside the SegmentTimeline element, inside SegmentTemplate at the Representation
 * level. When this feature isn't enabled, the segment durations in your DASH manifest are approximate. The segment
 * duration information appears in the duration attribute of the SegmentTemplate element.
 */
final class DashIsoWriteSegmentTimelineInRepresentation
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
