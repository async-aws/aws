<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the XAVC 4k (Long GOP) Bitrate Class to set the bitrate of your output. Outputs of the same class have
 * similar image quality over the operating points that are valid for that class.
 */
final class Xavc4kProfileBitrateClass
{
    public const BITRATE_CLASS_100 = 'BITRATE_CLASS_100';
    public const BITRATE_CLASS_140 = 'BITRATE_CLASS_140';
    public const BITRATE_CLASS_200 = 'BITRATE_CLASS_200';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BITRATE_CLASS_100 => true,
            self::BITRATE_CLASS_140 => true,
            self::BITRATE_CLASS_200 => true,
        ][$value]);
    }
}
