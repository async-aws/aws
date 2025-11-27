<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Keep this setting enabled to have MediaConvert use the font style and position information from the captions source
 * in the output. This option is available only when your input captions are IMSC, SMPTE-TT, or TTML. Disable this
 * setting for simplified output captions.
 */
final class ImscStylePassthrough
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
