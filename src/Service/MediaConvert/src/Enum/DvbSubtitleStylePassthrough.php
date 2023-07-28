<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set Style passthrough to ENABLED to use the available style, color, and position information from your input
 * captions. MediaConvert uses default settings for any missing style and position information in your input captions.
 * Set Style passthrough to DISABLED, or leave blank, to ignore the style and position information from your input
 * captions and use default settings: white text with black outlining, bottom-center positioning, and automatic sizing.
 * Whether you set Style passthrough to enabled or not, you can also choose to manually override any of the individual
 * style and position settings.
 */
final class DvbSubtitleStylePassthrough
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
