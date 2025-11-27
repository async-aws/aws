<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether your video overlay repeats or plays only once. To repeat your video overlay on a loop: Keep the
 * default value, Repeat. Your overlay will repeat for the duration of the base input video. To playback your video
 * overlay only once: Choose Once. With either option, you can end playback at a time that you specify by entering a
 * value for End timecode.
 */
final class VideoOverlayPlayBackMode
{
    public const ONCE = 'ONCE';
    public const REPEAT = 'REPEAT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ONCE => true,
            self::REPEAT => true,
        ][$value]);
    }
}
