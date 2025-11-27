<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use this setting if your input has video and audio durations that don't align, and your output or player has strict
 * alignment requirements. Examples: Input audio track has a delayed start. Input video track ends before audio ends.
 * When you set Pad video to Black, MediaConvert generates black video frames so that output video and audio durations
 * match. Black video frames are added at the beginning or end, depending on your input. To keep the default behavior
 * and not generate black video, set Pad video to Disabled or leave blank.
 */
final class PadVideo
{
    public const BLACK = 'BLACK';
    public const DISABLED = 'DISABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BLACK => true,
            self::DISABLED => true,
        ][$value]);
    }
}
