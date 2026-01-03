<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Includes or excludes EXT-X-PROGRAM-DATE-TIME tag in .m3u8 manifest files. The value is calculated as follows: either
 * the program date and time are initialized using the input timecode source, or the time is initialized using the input
 * timecode source and the date is initialized using the timestamp_offset.
 */
final class HlsProgramDateTime
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
