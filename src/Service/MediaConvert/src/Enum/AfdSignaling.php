<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * This setting only applies to H.264, H.265, and MPEG2 outputs. Use Insert AFD signaling to specify whether the service
 * includes AFD values in the output video data and what those values are. * Choose None to remove all AFD values from
 * this output. * Choose Fixed to ignore input AFD values and instead encode the value specified in the job. * Choose
 * Auto to calculate output AFD values based on the input AFD scaler data.
 */
final class AfdSignaling
{
    public const AUTO = 'AUTO';
    public const FIXED = 'FIXED';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::FIXED => true,
            self::NONE => true,
        ][$value]);
    }
}
