<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Only applies when you set Deinterlace mode to Deinterlace or Adaptive. Interpolate produces sharper pictures, while
 * blend produces smoother motion. If your source file includes a ticker, such as a scrolling headline at the bottom of
 * the frame: Choose Interpolate ticker or Blend ticker. To apply field doubling: Choose Linear interpolation. Note that
 * Linear interpolation may introduce video artifacts into your output.
 */
final class DeinterlaceAlgorithm
{
    public const BLEND = 'BLEND';
    public const BLEND_TICKER = 'BLEND_TICKER';
    public const INTERPOLATE = 'INTERPOLATE';
    public const INTERPOLATE_TICKER = 'INTERPOLATE_TICKER';
    public const LINEAR_INTERPOLATION = 'LINEAR_INTERPOLATION';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BLEND => true,
            self::BLEND_TICKER => true,
            self::INTERPOLATE => true,
            self::INTERPOLATE_TICKER => true,
            self::LINEAR_INTERPOLATION => true,
        ][$value]);
    }
}
