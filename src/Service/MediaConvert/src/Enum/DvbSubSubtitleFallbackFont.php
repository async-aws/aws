<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the font that you want the service to use for your burn in captions when your input captions specify a font
 * that MediaConvert doesn't support. When you set Fallback font to best match, or leave blank, MediaConvert uses a
 * supported font that most closely matches the font that your input captions specify. When there are multiple
 * unsupported fonts in your input captions, MediaConvert matches each font with the supported font that matches best.
 * When you explicitly choose a replacement font, MediaConvert uses that font to replace all unsupported fonts from your
 * input.
 */
final class DvbSubSubtitleFallbackFont
{
    public const BEST_MATCH = 'BEST_MATCH';
    public const MONOSPACED_SANSSERIF = 'MONOSPACED_SANSSERIF';
    public const MONOSPACED_SERIF = 'MONOSPACED_SERIF';
    public const PROPORTIONAL_SANSSERIF = 'PROPORTIONAL_SANSSERIF';
    public const PROPORTIONAL_SERIF = 'PROPORTIONAL_SERIF';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BEST_MATCH => true,
            self::MONOSPACED_SANSSERIF => true,
            self::MONOSPACED_SERIF => true,
            self::PROPORTIONAL_SANSSERIF => true,
            self::PROPORTIONAL_SERIF => true,
        ][$value]);
    }
}
