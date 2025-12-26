<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When enabled, include 'clap' atom if appropriate for the video output settings.
 */
final class MovClapAtom
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';

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
