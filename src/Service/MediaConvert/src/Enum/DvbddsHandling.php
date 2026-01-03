<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how MediaConvert handles the display definition segment (DDS). To exclude the DDS from this set of captions:
 * Keep the default, None. To include the DDS: Choose Specified. When you do, also specify the offset coordinates of the
 * display window with DDS x-coordinate and DDS y-coordinate. To include the DDS, but not include display window data:
 * Choose No display window. When you do, you can write position metadata to the page composition segment (PCS) with DDS
 * x-coordinate and DDS y-coordinate. For video resolutions with a height of 576 pixels or less, MediaConvert doesn't
 * include the DDS, regardless of the value you choose for DDS handling. All burn-in and DVB-Sub font settings must
 * match. To include the DDS, with optimized subtitle placement and reduced data overhead: We recommend that you choose
 * Specified (optimal). This option provides the same visual positioning as Specified while using less bandwidth. This
 * also supports resolutions higher than 1080p while maintaining full DVB-Sub compatibility. When you do, also specify
 * the offset coordinates of the display window with DDS x-coordinate and DDS y-coordinate.
 */
final class DvbddsHandling
{
    public const NONE = 'NONE';
    public const NO_DISPLAY_WINDOW = 'NO_DISPLAY_WINDOW';
    public const SPECIFIED = 'SPECIFIED';
    public const SPECIFIED_OPTIMAL = 'SPECIFIED_OPTIMAL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::NO_DISPLAY_WINDOW => true,
            self::SPECIFIED => true,
            self::SPECIFIED_OPTIMAL => true,
        ][$value]);
    }
}
