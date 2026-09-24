<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose how MediaConvert determines segment boundaries when you passthrough video to a segmented ABR output (HLS,
 * DASH, or CMAF). This setting applies only to ABR outputs. Keep the default value, Auto, to let MediaConvert choose
 * based on your input: when your input is a segmented HLS or DASH source, MediaConvert reproduces your input's own
 * segment boundaries, with one output segment per input segment; for all other inputs, MediaConvert places boundaries
 * by duration, cutting at the first eligible IDR-frame at or after each configured Segment length or Fragment length
 * target. Choose Duration based to always place boundaries by duration, at the first eligible IDR-frame at or after
 * each configured Segment length or Fragment length target, regardless of your input. When your input GOP duration does
 * not evenly divide your target segment length, output segment durations will vary. Choose GOP count to place a fixed
 * number of input GOPs in every segment, and specify GOPs per segment. Every segment contains the same number of input
 * GOPs, which produces consistent segment durations when your input GOP cadence is constant. In this mode MediaConvert
 * ignores your configured Segment length and Fragment length for video boundary placement. Ad avails and input
 * discontinuities are still honored as segment boundaries.
 */
final class PassthroughSegmentationMode
{
    public const AUTO = 'AUTO';
    public const DURATION_BASED = 'DURATION_BASED';
    public const GOP_COUNT = 'GOP_COUNT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::DURATION_BASED => true,
            self::GOP_COUNT => true,
        ][$value]);
    }
}
