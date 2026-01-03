<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify font outline color. Leave Outline color blank and set Style passthrough to enabled to use the font outline
 * color data from your input captions, if present. Within your job settings, all of your DVB-Sub settings must be
 * identical.
 */
final class DvbSubtitleOutlineColor
{
    public const AUTO = 'AUTO';
    public const BLACK = 'BLACK';
    public const BLUE = 'BLUE';
    public const GREEN = 'GREEN';
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
            self::RED => true,
            self::WHITE => true,
            self::YELLOW => true,
        ][$value]);
    }
}
