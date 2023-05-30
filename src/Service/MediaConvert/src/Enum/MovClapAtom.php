<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When enabled, include 'clap' atom if appropriate for the video output settings.
 */
final class MovClapAtom
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';

    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
