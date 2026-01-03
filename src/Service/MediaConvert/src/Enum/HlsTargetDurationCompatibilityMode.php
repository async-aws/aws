<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to LEGACY, the segment target duration is always rounded up to the nearest integer value above its current
 * value in seconds. When set to SPEC\\_COMPLIANT, the segment target duration is rounded up to the nearest integer
 * value if fraction seconds are greater than or equal to 0.5 (>= 0.5) and rounded down if less than 0.5 (< 0.5). You
 * may need to use LEGACY if your client needs to ensure that the target duration is always longer than the actual
 * duration of the segment. Some older players may experience interrupted playback when the actual duration of a track
 * in a segment is longer than the target duration.
 */
final class HlsTargetDurationCompatibilityMode
{
    public const LEGACY = 'LEGACY';
    public const SPEC_COMPLIANT = 'SPEC_COMPLIANT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LEGACY => true,
            self::SPEC_COMPLIANT => true,
        ][$value]);
    }
}
