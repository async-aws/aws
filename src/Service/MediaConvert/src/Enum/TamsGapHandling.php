<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how MediaConvert handles gaps between media segments in your TAMS source. Gaps can occur in live streams due
 * to network issues or other interruptions. Choose from the following options: * Skip gaps - Default. Skip over gaps
 * and join segments together. This creates a continuous output with no blank frames, but may cause timeline
 * discontinuities. * Fill with black - Insert black frames to fill gaps between segments. This maintains timeline
 * continuity but adds black frames where content is missing. * Hold last frame - Repeat the last frame before a gap
 * until the next segment begins. This maintains visual continuity during gaps.
 */
final class TamsGapHandling
{
    public const FILL_WITH_BLACK = 'FILL_WITH_BLACK';
    public const HOLD_LAST_FRAME = 'HOLD_LAST_FRAME';
    public const SKIP_GAPS = 'SKIP_GAPS';

    public static function exists(string $value): bool
    {
        return isset([
            self::FILL_WITH_BLACK => true,
            self::HOLD_LAST_FRAME => true,
            self::SKIP_GAPS => true,
        ][$value]);
    }
}
