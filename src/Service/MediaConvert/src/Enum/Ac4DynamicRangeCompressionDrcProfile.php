<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the Dolby AC-4 dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the
 * Dolby AC-4 stream for the specified decoder mode. For information about the Dolby AC-4 DRC profiles, see the Dolby
 * AC-4 specification.
 */
final class Ac4DynamicRangeCompressionDrcProfile
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
