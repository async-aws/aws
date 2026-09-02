<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the font style for TTML captions output.
 */
final class TtmlFontStyle
{
    public const ITALIC = 'ITALIC';
    public const NORMAL = 'NORMAL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ITALIC => true,
            self::NORMAL => true,
        ][$value]);
    }
}
