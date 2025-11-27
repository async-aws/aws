<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * To use the available style, color, and position information from your input captions: Set Style passthrough to
 * Enabled. Note that MediaConvert uses default settings for any missing style or position information in your input
 * captions To ignore the style and position information from your input captions and use default settings: Leave blank
 * or keep the default value, Disabled. Default settings include white text with black outlining, bottom-center
 * positioning, and automatic sizing. Whether you set Style passthrough to enabled or not, you can also choose to
 * manually override any of the individual style and position settings. You can also override any fonts by manually
 * specifying custom font files.
 */
final class DvbSubtitleStylePassthrough
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
