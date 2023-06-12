<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Use Quality tuning level (qualityTuningLevel) to choose how you want to trade off encoding speed for output
 * video quality. The default behavior is faster, lower quality, multi-pass encoding.
 */
final class Vp9QualityTuningLevel
{
    public const MULTI_PASS = 'MULTI_PASS';
    public const MULTI_PASS_HQ = 'MULTI_PASS_HQ';

    public static function exists(string $value): bool
    {
        return isset([
            self::MULTI_PASS => true,
            self::MULTI_PASS_HQ => true,
        ][$value]);
    }
}
