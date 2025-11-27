<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how MediaConvert maps brightness and colors from your HDR input to your SDR output. The mode that you select
 * represents a creative choice, with different tradeoffs in the details and tones of your output. To maintain details
 * in bright or saturated areas of your output: Choose Preserve details. For some sources, your SDR output may look less
 * bright and less saturated when compared to your HDR source. MediaConvert automatically applies this mode for HLG
 * sources, regardless of your choice. For a bright and saturated output: Choose Vibrant. We recommend that you choose
 * this mode when any of your source content is HDR10, and for the best results when it is mastered for 1000 nits. You
 * may notice loss of details in bright or saturated areas of your output. HDR to SDR tone mapping has no effect when
 * your input is SDR.
 */
final class HDRToSDRToneMapper
{
    public const PRESERVE_DETAILS = 'PRESERVE_DETAILS';
    public const VIBRANT = 'VIBRANT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PRESERVE_DETAILS => true,
            self::VIBRANT => true,
        ][$value]);
    }
}
