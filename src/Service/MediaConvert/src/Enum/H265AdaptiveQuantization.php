<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you set Adaptive Quantization (H265AdaptiveQuantization) to Auto (AUTO), or leave blank, MediaConvert
 * automatically applies quantization to improve the video quality of your output. Set Adaptive Quantization to Low
 * (LOW), Medium (MEDIUM), High (HIGH), Higher (HIGHER), or Max (MAX) to manually control the strength of the
 * quantization filter. When you do, you can specify a value for Spatial Adaptive Quantization
 * (H265SpatialAdaptiveQuantization), Temporal Adaptive Quantization (H265TemporalAdaptiveQuantization), and Flicker
 * Adaptive Quantization (H265FlickerAdaptiveQuantization), to further control the quantization filter. Set Adaptive
 * Quantization to Off (OFF) to apply no quantization to your output.
 */
final class H265AdaptiveQuantization
{
    public const AUTO = 'AUTO';
    public const HIGH = 'HIGH';
    public const HIGHER = 'HIGHER';
    public const LOW = 'LOW';
    public const MAX = 'MAX';
    public const MEDIUM = 'MEDIUM';
    public const OFF = 'OFF';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::HIGH => true,
            self::HIGHER => true,
            self::LOW => true,
            self::MAX => true,
            self::MEDIUM => true,
            self::OFF => true,
        ][$value]);
    }
}
