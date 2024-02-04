<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * VBR Quality Level - Only used if rate_control_mode is VBR.
 */
final class AacVbrQuality
{
    public const HIGH = 'HIGH';
    public const LOW = 'LOW';
    public const MEDIUM_HIGH = 'MEDIUM_HIGH';
    public const MEDIUM_LOW = 'MEDIUM_LOW';

    public static function exists(string $value): bool
    {
        return isset([
            self::HIGH => true,
            self::LOW => true,
            self::MEDIUM_HIGH => true,
            self::MEDIUM_LOW => true,
        ][$value]);
    }
}
