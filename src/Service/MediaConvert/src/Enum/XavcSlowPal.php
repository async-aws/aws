<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Ignore this setting unless your input frame rate is 23.976 or 24 frames per second (fps). Enable slow PAL to create a
 * 25 fps output by relabeling the video frames and resampling your audio. Note that enabling this setting will slightly
 * reduce the duration of your video. Related settings: You must also set Frame rate to 25.
 */
final class XavcSlowPal
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
