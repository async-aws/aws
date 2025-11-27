<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how MediaConvert writes style information in your output WebVTT captions. To use the available style, color,
 * and position information from your input captions: Choose Enabled. MediaConvert uses default settings when style and
 * position information is missing from your input captions. To recreate the input captions exactly: Choose Strict.
 * MediaConvert automatically applies timing adjustments, including adjustments for frame rate conversion, ad avails,
 * and input clipping. Your input captions format must be WebVTT. To ignore the style and position information from your
 * input captions and use simplified output captions: Keep the default value, Disabled. Or leave blank. To use the
 * available style, color, and position information from your input captions, while merging cues with identical time
 * ranges: Choose merge. This setting can help prevent positioning overlaps for certain players that expect a single
 * single cue for any given time range.
 */
final class WebvttStylePassthrough
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const MERGE = 'MERGE';
    public const STRICT = 'STRICT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
            self::MERGE => true,
            self::STRICT => true,
        ][$value]);
    }
}
