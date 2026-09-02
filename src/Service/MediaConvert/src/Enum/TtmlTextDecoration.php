<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the text decoration for TTML captions output.
 */
final class TtmlTextDecoration
{
    public const NONE = 'NONE';
    public const UNDERLINE = 'UNDERLINE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::UNDERLINE => true,
        ][$value]);
    }
}
