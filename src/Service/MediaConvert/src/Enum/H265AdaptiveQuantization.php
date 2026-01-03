<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you set Adaptive Quantization to Auto, or leave blank, MediaConvert automatically applies quantization to
 * improve the video quality of your output. Set Adaptive Quantization to Low, Medium, High, Higher, or Max to manually
 * control the strength of the quantization filter. When you do, you can specify a value for Spatial Adaptive
 * Quantization, Temporal Adaptive Quantization, and Flicker Adaptive Quantization, to further control the quantization
 * filter. Set Adaptive Quantization to Off to apply no quantization to your output.
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
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
