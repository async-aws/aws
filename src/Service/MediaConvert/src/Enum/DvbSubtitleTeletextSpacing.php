<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether the Text spacing in your captions is set by the captions grid, or varies depending on letter width.
 * Choose fixed grid to conform to the spacing specified in the captions file more accurately. Choose proportional to
 * make the text easier to read for closed captions. Within your job settings, all of your DVB-Sub settings must be
 * identical.
 */
final class DvbSubtitleTeletextSpacing
{
    public const AUTO = 'AUTO';
    public const FIXED_GRID = 'FIXED_GRID';
    public const PROPORTIONAL = 'PROPORTIONAL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::FIXED_GRID => true,
            self::PROPORTIONAL => true,
        ][$value]);
    }
}
