<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the video Scaling behavior when your output has a different resolution than your input. For more information,
 * see https://docs.aws.amazon.com/mediaconvert/latest/ug/video-scaling.html.
 */
final class ScalingBehavior
{
    public const DEFAULT = 'DEFAULT';
    public const FILL = 'FILL';
    public const FIT = 'FIT';
    public const FIT_NO_UPSCALE = 'FIT_NO_UPSCALE';
    public const STRETCH_TO_OUTPUT = 'STRETCH_TO_OUTPUT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::FILL => true,
            self::FIT => true,
            self::FIT_NO_UPSCALE => true,
            self::STRETCH_TO_OUTPUT => true,
        ][$value]);
    }
}
