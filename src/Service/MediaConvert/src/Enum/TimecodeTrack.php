<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * To include a timecode track in your MP4 output: Choose Enabled. MediaConvert writes the timecode track in the Null
 * Media Header box (NMHD), without any timecode text formatting information. You can also specify dropframe or
 * non-dropframe timecode under the Drop Frame Timecode setting. To not include a timecode track: Keep the default
 * value, Disabled.
 */
final class TimecodeTrack
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
