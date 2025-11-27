<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the Unit type to use when you enter a value for X position, Y position, Width, or Height. You can choose
 * Pixels or Percentage. Leave blank to use the default value, Pixels.
 */
final class VideoOverlayUnit
{
    public const PERCENTAGE = 'PERCENTAGE';
    public const PIXELS = 'PIXELS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PERCENTAGE => true,
            self::PIXELS => true,
        ][$value]);
    }
}
