<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optionally specify the amount of sharpening to apply when you use the Advanced input filter. Sharpening adds contrast
 * to the edges of your video content and can reduce softness. To apply no sharpening: Keep the default value, Off. To
 * apply a minimal amount of sharpening choose Low, or for the maximum choose High.
 */
final class AdvancedInputFilterSharpen
{
    public const HIGH = 'HIGH';
    public const LOW = 'LOW';
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
            self::OFF => true,
        ][$value]);
    }
}
