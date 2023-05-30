<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how MediaConvert handles the display definition segment (DDS). To exclude the DDS from this set of captions:
 * Keep the default, None. To include the DDS: Choose Specified. When you do, also specify the offset coordinates of the
 * display window with DDS x-coordinate and DDS y-coordinate. To include the DDS, but not include display window data:
 * Choose No display window. When you do, you can write position metadata to the page composition segment (PCS) with DDS
 * x-coordinate and DDS y-coordinate. For video resolutions with a height of 576 pixels or less, MediaConvert doesn't
 * include the DDS, regardless of the value you choose for DDS handling. All burn-in and DVB-Sub font settings must
 * match.
 */
final class DvbddsHandling
{
    public const NONE = 'NONE';
    public const NO_DISPLAY_WINDOW = 'NO_DISPLAY_WINDOW';
    public const SPECIFIED = 'SPECIFIED';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::NO_DISPLAY_WINDOW => true,
            self::SPECIFIED => true,
        ][$value]);
    }
}
