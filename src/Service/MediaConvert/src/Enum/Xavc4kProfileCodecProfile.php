<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the codec profile for this output. Choose High, 8-bit, 4:2:0 (HIGH) or High, 10-bit, 4:2:2 (HIGH_422). These
 * profiles are specified in ITU-T H.264.
 */
final class Xavc4kProfileCodecProfile
{
    public const HIGH = 'HIGH';
    public const HIGH_422 = 'HIGH_422';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HIGH => true,
            self::HIGH_422 => true,
        ][$value]);
    }
}
