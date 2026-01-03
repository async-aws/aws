<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you want to add Dolby dynamic range compression (DRC) signaling to your output stream, we recommend that you use
 * the mode-specific settings instead of Dynamic range compression profile. The mode-specific settings are Dynamic range
 * compression profile, line mode and Dynamic range compression profile, RF mode. Note that when you specify values for
 * all three settings, MediaConvert ignores the value of this setting in favor of the mode-specific settings. If you do
 * use this setting instead of the mode-specific settings, choose None to leave out DRC signaling. Keep the default Film
 * standard to set the profile to Dolby's film standard profile for all operating modes.
 */
final class Ac3DynamicRangeCompressionProfile
{
    public const FILM_STANDARD = 'FILM_STANDARD';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FILM_STANDARD => true,
            self::NONE => true,
        ][$value]);
    }
}
