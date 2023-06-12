<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the method that you want MediaConvert to use when increasing or decreasing the frame rate. For numerically
 * simple conversions, such as 60 fps to 30 fps: We recommend that you keep the default value, Drop duplicate. For
 * numerically complex conversions, to avoid stutter: Choose Interpolate. This results in a smooth picture, but might
 * introduce undesirable video artifacts. For complex frame rate conversions, especially if your source video has
 * already been converted from its original cadence: Choose FrameFormer to do motion-compensated interpolation.
 * FrameFormer uses the best conversion method frame by frame. Note that using FrameFormer increases the transcoding
 * time and incurs a significant add-on cost. When you choose FrameFormer, your input video resolution must be at least
 * 128x96.
 */
final class ProresFramerateConversionAlgorithm
{
    public const DUPLICATE_DROP = 'DUPLICATE_DROP';
    public const FRAMEFORMER = 'FRAMEFORMER';
    public const INTERPOLATE = 'INTERPOLATE';

    public static function exists(string $value): bool
    {
        return isset([
            self::DUPLICATE_DROP => true,
            self::FRAMEFORMER => true,
            self::INTERPOLATE => true,
        ][$value]);
    }
}
