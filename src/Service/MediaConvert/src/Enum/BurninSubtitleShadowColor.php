<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the color of the shadow cast by the captions. Leave Shadow color (ShadowColor) blank and set Style
 * passthrough (StylePassthrough) to enabled to use the shadow color data from your input captions, if present.
 */
final class BurninSubtitleShadowColor
{
    public const AUTO = 'AUTO';
    public const BLACK = 'BLACK';
    public const NONE = 'NONE';
    public const WHITE = 'WHITE';

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
