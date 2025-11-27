<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the Dolby dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the Dolby
 * stream for the line operating mode. Default value: Film light Related setting: To have MediaConvert use the value you
 * specify here, keep the default value, Custom for the setting Dynamic range control. Otherwise, MediaConvert ignores
 * Dynamic range compression line. For information about the Dolby DRC operating modes and profiles, see the Dynamic
 * Range Control chapter of the Dolby Metadata Guide at
 * https://developer.dolby.com/globalassets/professional/documents/dolby-metadata-guide.pdf.
 */
final class Eac3AtmosDynamicRangeCompressionLine
{
    public const FILM_LIGHT = 'FILM_LIGHT';
    public const FILM_STANDARD = 'FILM_STANDARD';
    public const MUSIC_LIGHT = 'MUSIC_LIGHT';
    public const MUSIC_STANDARD = 'MUSIC_STANDARD';
    public const NONE = 'NONE';
    public const SPEECH = 'SPEECH';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FILM_LIGHT => true,
            self::FILM_STANDARD => true,
            self::MUSIC_LIGHT => true,
            self::MUSIC_STANDARD => true,
            self::NONE => true,
            self::SPEECH => true,
        ][$value]);
    }
}
