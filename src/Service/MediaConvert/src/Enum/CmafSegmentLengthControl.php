<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how you want MediaConvert to determine segment lengths in this output group. To use the exact value that you
 * specify under Segment length: Choose Exact. Note that this might result in additional I-frames in the output GOP. To
 * create segment lengths that are a multiple of the GOP: Choose Multiple of GOP. MediaConvert will round up the segment
 * lengths to match the next GOP boundary. To have MediaConvert automatically determine a segment duration that is a
 * multiple of both the audio packets and the frame rates: Choose Match. When you do, also specify a target segment
 * duration under Segment length. This is useful for some ad-insertion or segment replacement workflows. Note that Match
 * has the following requirements: - Output containers: Include at least one video output and at least one audio output.
 * Audio-only outputs are not supported. - Output frame rate: Follow source is not supported. - Multiple output frame
 * rates: When you specify multiple outputs, we recommend they share a similar frame rate (as in X/3, X/2, X, or 2X).
 * For example: 5, 15, 30 and 60. Or: 25 and 50. (Outputs must share an integer multiple.) - Output audio codec: Specify
 * Advanced Audio Coding (AAC). - Output sample rate: Choose 48kHz.
 */
final class CmafSegmentLengthControl
{
    public const EXACT = 'EXACT';
    public const GOP_MULTIPLE = 'GOP_MULTIPLE';
    public const MATCH = 'MATCH';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXACT => true,
            self::GOP_MULTIPLE => true,
            self::MATCH => true,
        ][$value]);
    }
}
