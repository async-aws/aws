<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Represents the Profile and Tier, per the HEVC (H.265) specification. Selections are grouped as [Profile] / [Tier], so
 * "Main/High" represents Main Profile with High Tier. 4:2:2 profiles are only available with the HEVC 4:2:2 License.
 */
final class H265CodecProfile
{
    public const MAIN10_HIGH = 'MAIN10_HIGH';
    public const MAIN10_MAIN = 'MAIN10_MAIN';
    public const MAIN_422_10BIT_HIGH = 'MAIN_422_10BIT_HIGH';
    public const MAIN_422_10BIT_MAIN = 'MAIN_422_10BIT_MAIN';
    public const MAIN_422_8BIT_HIGH = 'MAIN_422_8BIT_HIGH';
    public const MAIN_422_8BIT_MAIN = 'MAIN_422_8BIT_MAIN';
    public const MAIN_HIGH = 'MAIN_HIGH';
    public const MAIN_MAIN = 'MAIN_MAIN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MAIN10_HIGH => true,
            self::MAIN10_MAIN => true,
            self::MAIN_422_10BIT_HIGH => true,
            self::MAIN_422_10BIT_MAIN => true,
            self::MAIN_422_8BIT_HIGH => true,
            self::MAIN_422_8BIT_MAIN => true,
            self::MAIN_HIGH => true,
            self::MAIN_MAIN => true,
        ][$value]);
    }
}
