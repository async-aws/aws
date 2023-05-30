<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If set to TRUE_PEAK, calculate and log the TruePeak for each output's audio track loudness.
 */
final class AudioNormalizationPeakCalculation
{
    public const NONE = 'NONE';
    public const TRUE_PEAK = 'TRUE_PEAK';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::TRUE_PEAK => true,
        ][$value]);
    }
}
