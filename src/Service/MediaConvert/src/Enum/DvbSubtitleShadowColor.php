<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the color of the shadow cast by the captions. Leave Shadow color blank and set Style passthrough to enabled
 * to use the shadow color data from your input captions, if present. Within your job settings, all of your DVB-Sub
 * settings must be identical.
 */
final class DvbSubtitleShadowColor
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
