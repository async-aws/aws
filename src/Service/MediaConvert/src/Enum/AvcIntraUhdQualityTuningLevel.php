<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Use Quality tuning level (qualityTuningLevel) to choose how many transcoding passes MediaConvert does with
 * your video. When you choose Multi-pass (MULTI_PASS), your video quality is better and your output bitrate is more
 * accurate. That is, the actual bitrate of your output is closer to the target bitrate defined in the specification.
 * When you choose Single-pass (SINGLE_PASS), your encoding time is faster. The default behavior is Single-pass
 * (SINGLE_PASS).
 */
final class AvcIntraUhdQualityTuningLevel
{
    public const MULTI_PASS = 'MULTI_PASS';
    public const SINGLE_PASS = 'SINGLE_PASS';

    public static function exists(string $value): bool
    {
        return isset([
            self::MULTI_PASS => true,
            self::SINGLE_PASS => true,
        ][$value]);
    }
}
