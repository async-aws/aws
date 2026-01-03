<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optionally include or suppress markers at the end of your output that signal the end of the video stream. To include
 * end of stream markers: Leave blank or keep the default value, Include. To not include end of stream markers: Choose
 * Suppress. This is useful when your output will be inserted into another stream.
 */
final class H265EndOfStreamMarkers
{
    public const INCLUDE = 'INCLUDE';
    public const SUPPRESS = 'SUPPRESS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INCLUDE => true,
            self::SUPPRESS => true,
        ][$value]);
    }
}
