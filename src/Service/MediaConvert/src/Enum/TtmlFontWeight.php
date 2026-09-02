<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the font weight for TTML captions output.
 */
final class TtmlFontWeight
{
    public const BOLD = 'BOLD';
    public const NORMAL = 'NORMAL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BOLD => true,
            self::NORMAL => true,
        ][$value]);
    }
}
