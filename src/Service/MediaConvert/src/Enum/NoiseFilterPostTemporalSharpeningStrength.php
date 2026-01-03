<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Post temporal sharpening strength to define the amount of sharpening the transcoder applies to your output. Set
 * Post temporal sharpening strength to Low, Medium, or High to indicate the amount of sharpening.
 */
final class NoiseFilterPostTemporalSharpeningStrength
{
    public const HIGH = 'HIGH';
    public const LOW = 'LOW';
    public const MEDIUM = 'MEDIUM';
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
        ][$value]);
    }
}
