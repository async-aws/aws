<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When enabled the output audio is corrected using the chosen algorithm. If disabled, the audio will be measured but
 * not adjusted.
 */
final class AudioNormalizationAlgorithmControl
{
    public const CORRECT_AUDIO = 'CORRECT_AUDIO';
    public const MEASURE_ONLY = 'MEASURE_ONLY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CORRECT_AUDIO => true,
            self::MEASURE_ONLY => true,
        ][$value]);
    }
}
