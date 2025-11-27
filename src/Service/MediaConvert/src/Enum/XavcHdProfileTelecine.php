<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Ignore this setting unless you set Frame rate (framerateNumerator divided by framerateDenominator) to 29.970. If your
 * input framerate is 23.976, choose Hard. Otherwise, keep the default value None. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/working-with-telecine-and-inverse-telecine.html.
 */
final class XavcHdProfileTelecine
{
    public const HARD = 'HARD';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HARD => true,
            self::NONE => true,
        ][$value]);
    }
}
