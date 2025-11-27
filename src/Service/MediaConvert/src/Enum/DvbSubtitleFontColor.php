<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the color of the captions text. Leave Font color blank and set Style passthrough to enabled to use the font
 * color data from your input captions, if present. Within your job settings, all of your DVB-Sub settings must be
 * identical.
 */
final class DvbSubtitleFontColor
{
    public const AUTO = 'AUTO';
    public const BLACK = 'BLACK';
    public const BLUE = 'BLUE';
    public const GREEN = 'GREEN';
    public const HEX = 'HEX';
    public const RED = 'RED';
    public const WHITE = 'WHITE';
    public const YELLOW = 'YELLOW';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::BLACK => true,
            self::BLUE => true,
            self::GREEN => true,
            self::HEX => true,
            self::RED => true,
            self::WHITE => true,
            self::YELLOW => true,
        ][$value]);
    }
}
