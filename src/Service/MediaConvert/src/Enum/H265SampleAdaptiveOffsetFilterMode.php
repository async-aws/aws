<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify Sample Adaptive Offset (SAO) filter strength. Adaptive mode dynamically selects best strength based on
 * content.
 */
final class H265SampleAdaptiveOffsetFilterMode
{
    public const ADAPTIVE = 'ADAPTIVE';
    public const DEFAULT = 'DEFAULT';
    public const OFF = 'OFF';

    public static function exists(string $value): bool
    {
        return isset([
            self::ADAPTIVE => true,
            self::DEFAULT => true,
            self::OFF => true,
        ][$value]);
    }
}
