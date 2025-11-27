<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set Style passthrough to ENABLED to use the available style, color, and position information from your input
 * captions. MediaConvert uses default settings for any missing style and position information in your input captions.
 * Set Style passthrough to DISABLED, or leave blank, to ignore the style and position information from your input
 * captions and use simplified output captions.
 */
final class SrtStylePassthrough
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
