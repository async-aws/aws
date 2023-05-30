<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether the text spacing (TeletextSpacing) in your captions is set by the captions grid, or varies depending
 * on letter width. Choose fixed grid (FIXED_GRID) to conform to the spacing specified in the captions file more
 * accurately. Choose proportional (PROPORTIONAL) to make the text easier to read for closed captions.
 */
final class BurninSubtitleTeletextSpacing
{
    public const AUTO = 'AUTO';
    public const FIXED_GRID = 'FIXED_GRID';
    public const PROPORTIONAL = 'PROPORTIONAL';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::FIXED_GRID => true,
            self::PROPORTIONAL => true,
        ][$value]);
    }
}
