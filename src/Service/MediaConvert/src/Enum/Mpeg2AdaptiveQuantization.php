<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the strength of any adaptive quantization filters that you enable. The value that you choose here applies to
 * the following settings: Spatial adaptive quantization, and Temporal adaptive quantization.
 */
final class Mpeg2AdaptiveQuantization
{
    public const HIGH = 'HIGH';
    public const LOW = 'LOW';
    public const MEDIUM = 'MEDIUM';
    public const OFF = 'OFF';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HIGH => true,
            self::LOW => true,
            self::MEDIUM => true,
            self::OFF => true,
        ][$value]);
    }
}
