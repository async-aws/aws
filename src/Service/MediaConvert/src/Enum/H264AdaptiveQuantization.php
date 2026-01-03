<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Keep the default value, Auto, for this setting to have MediaConvert automatically apply the best types of
 * quantization for your video content. When you want to apply your quantization settings manually, you must set
 * H264AdaptiveQuantization to a value other than Auto. Use this setting to specify the strength of any adaptive
 * quantization filters that you enable. If you don't want MediaConvert to do any adaptive quantization in this
 * transcode, set Adaptive quantization to Off. Related settings: The value that you choose here applies to the
 * following settings: H264FlickerAdaptiveQuantization, H264SpatialAdaptiveQuantization, and
 * H264TemporalAdaptiveQuantization.
 */
final class H264AdaptiveQuantization
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
