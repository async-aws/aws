<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Post temporal sharpening strength (postTemporalSharpeningStrength) to define the amount of sharpening the
 * transcoder applies to your output. Set Post temporal sharpening strength to Low (LOW), Medium (MEDIUM), or High
 * (HIGH) to indicate the amount of sharpening.
 */
final class NoiseFilterPostTemporalSharpeningStrength
{
    public const HIGH = 'HIGH';
    public const LOW = 'LOW';
    public const MEDIUM = 'MEDIUM';

    public static function exists(string $value): bool
    {
        return isset([
            self::HIGH => true,
            self::LOW => true,
            self::MEDIUM => true,
        ][$value]);
    }
}
