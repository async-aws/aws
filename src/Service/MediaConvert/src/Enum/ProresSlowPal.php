<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Ignore this setting unless your input frame rate is 23.976 or 24 frames per second (fps). Enable slow PAL to create a
 * 25 fps output. When you enable slow PAL, MediaConvert relabels the video frames to 25 fps and resamples your audio to
 * keep it synchronized with the video. Note that enabling this setting will slightly reduce the duration of your video.
 * Required settings: You must also set Framerate to 25. In your JSON job specification, set (framerateControl) to
 * (SPECIFIED), (framerateNumerator) to 25 and (framerateDenominator) to 1.
 */
final class ProresSlowPal
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
