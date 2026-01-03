<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optionally specify the level of sharpening to apply when you use the Bandwidth reduction filter. Sharpening adds
 * contrast to the edges of your video content and can reduce softness. Keep the default value Off to apply no
 * sharpening. Set Sharpening strength to Low to apply a minimal amount of sharpening, or High to apply a maximum amount
 * of sharpening.
 */
final class BandwidthReductionFilterSharpening
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
