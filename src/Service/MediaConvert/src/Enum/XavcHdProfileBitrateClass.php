<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the XAVC HD (Long GOP) Bitrate Class to set the bitrate of your output. Outputs of the same class have
 * similar image quality over the operating points that are valid for that class.
 */
final class XavcHdProfileBitrateClass
{
    public const BITRATE_CLASS_25 = 'BITRATE_CLASS_25';
    public const BITRATE_CLASS_35 = 'BITRATE_CLASS_35';
    public const BITRATE_CLASS_50 = 'BITRATE_CLASS_50';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BITRATE_CLASS_25 => true,
            self::BITRATE_CLASS_35 => true,
            self::BITRATE_CLASS_50 => true,
        ][$value]);
    }
}
