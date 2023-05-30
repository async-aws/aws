<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Source (TimecodeSource) to set how timecodes are handled within this job. To make sure that your video, audio,
 * captions, and markers are synchronized and that time-based features, such as image inserter, work correctly, choose
 * the Timecode source option that matches your assets. All timecodes are in a 24-hour format with frame number
 * (HH:MM:SS:FF). * Embedded (EMBEDDED) - Use the timecode that is in the input video. If no embedded timecode is in the
 * source, the service will use Start at 0 (ZEROBASED) instead. * Start at 0 (ZEROBASED) - Set the timecode of the
 * initial frame to 00:00:00:00. * Specified Start (SPECIFIEDSTART) - Set the timecode of the initial frame to a value
 * other than zero. You use Start timecode (Start) to provide this value.
 */
final class TimecodeSource
{
    public const EMBEDDED = 'EMBEDDED';
    public const SPECIFIEDSTART = 'SPECIFIEDSTART';
    public const ZEROBASED = 'ZEROBASED';

    public static function exists(string $value): bool
    {
        return isset([
            self::EMBEDDED => true,
            self::SPECIFIEDSTART => true,
            self::ZEROBASED => true,
        ][$value]);
    }
}
