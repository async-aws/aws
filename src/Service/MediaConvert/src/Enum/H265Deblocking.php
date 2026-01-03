<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Deblocking to improve the video quality of your output by smoothing the edges of macroblock artifacts created
 * during video compression. To reduce blocking artifacts at block boundaries, and improve overall video quality: Keep
 * the default value, Enabled. To not apply any deblocking: Choose Disabled. Visible block edge artifacts might appear
 * in the output, especially at lower bitrates.
 */
final class H265Deblocking
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
