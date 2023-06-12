<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you set Noise reducer (noiseReducer) to Temporal (TEMPORAL), the bandwidth and sharpness of your output is
 * reduced. You can optionally use Post temporal sharpening (postTemporalSharpening) to apply sharpening to the edges of
 * your output. Note that Post temporal sharpening will also make the bandwidth reduction from the Noise reducer
 * smaller. The default behavior, Auto (AUTO), allows the transcoder to determine whether to apply sharpening, depending
 * on your input type and quality. When you set Post temporal sharpening to Enabled (ENABLED), specify how much
 * sharpening is applied using Post temporal sharpening strength (postTemporalSharpeningStrength). Set Post temporal
 * sharpening to Disabled (DISABLED) to not apply sharpening.
 */
final class NoiseFilterPostTemporalSharpening
{
    public const AUTO = 'AUTO';
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
