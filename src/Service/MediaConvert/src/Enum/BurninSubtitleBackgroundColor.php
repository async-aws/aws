<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the color of the rectangle behind the captions. Leave background color blank and set Style passthrough to
 * enabled to use the background color data from your input captions, if present.
 */
final class BurninSubtitleBackgroundColor
{
    public const AUTO = 'AUTO';
    public const BLACK = 'BLACK';
    public const NONE = 'NONE';
    public const WHITE = 'WHITE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::BLACK => true,
            self::NONE => true,
            self::WHITE => true,
        ][$value]);
    }
}
