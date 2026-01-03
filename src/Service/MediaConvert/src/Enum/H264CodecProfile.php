<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * H.264 Profile. High 4:2:2 and 10-bit profiles are only available with the AVC-I License.
 */
final class H264CodecProfile
{
    public const BASELINE = 'BASELINE';
    public const HIGH = 'HIGH';
    public const HIGH_10BIT = 'HIGH_10BIT';
    public const HIGH_422 = 'HIGH_422';
    public const HIGH_422_10BIT = 'HIGH_422_10BIT';
    public const MAIN = 'MAIN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BASELINE => true,
            self::HIGH => true,
            self::HIGH_10BIT => true,
            self::HIGH_422 => true,
            self::HIGH_422_10BIT => true,
            self::MAIN => true,
        ][$value]);
    }
}
