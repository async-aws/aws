<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the presentation style of your input SCC captions. To use the same presentation style as your input: Keep the
 * default value, Disabled. To convert paint-on captions to pop-on: Choose Enabled. We also recommend that you choose
 * Enabled if you notice additional repeated lines in your output captions.
 */
final class CaptionSourceConvertPaintOnToPopOn
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
