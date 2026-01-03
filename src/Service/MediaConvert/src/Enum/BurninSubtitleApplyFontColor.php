<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Ignore this setting unless Style passthrough is set to Enabled and Font color set to Black, Yellow, Red, Green, Blue,
 * or Hex. Use Apply font color for additional font color controls. When you choose White text only, or leave blank,
 * your font color setting only applies to white text in your input captions. For example, if your font color setting is
 * Yellow, and your input captions have red and white text, your output captions will have red and yellow text. When you
 * choose ALL_TEXT, your font color setting applies to all of your output captions text.
 */
final class BurninSubtitleApplyFontColor
{
    public const ALL_TEXT = 'ALL_TEXT';
    public const WHITE_TEXT_ONLY = 'WHITE_TEXT_ONLY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_TEXT => true,
            self::WHITE_TEXT_ONLY => true,
        ][$value]);
    }
}
