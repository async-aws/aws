<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * H.265 Level.
 */
final class H265CodecLevel
{
    public const AUTO = 'AUTO';
    public const LEVEL_1 = 'LEVEL_1';
    public const LEVEL_2 = 'LEVEL_2';
    public const LEVEL_2_1 = 'LEVEL_2_1';
    public const LEVEL_3 = 'LEVEL_3';
    public const LEVEL_3_1 = 'LEVEL_3_1';
    public const LEVEL_4 = 'LEVEL_4';
    public const LEVEL_4_1 = 'LEVEL_4_1';
    public const LEVEL_5 = 'LEVEL_5';
    public const LEVEL_5_1 = 'LEVEL_5_1';
    public const LEVEL_5_2 = 'LEVEL_5_2';
    public const LEVEL_6 = 'LEVEL_6';
    public const LEVEL_6_1 = 'LEVEL_6_1';
    public const LEVEL_6_2 = 'LEVEL_6_2';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::LEVEL_1 => true,
            self::LEVEL_2 => true,
            self::LEVEL_2_1 => true,
            self::LEVEL_3 => true,
            self::LEVEL_3_1 => true,
            self::LEVEL_4 => true,
            self::LEVEL_4_1 => true,
            self::LEVEL_5 => true,
            self::LEVEL_5_1 => true,
            self::LEVEL_5_2 => true,
            self::LEVEL_6 => true,
            self::LEVEL_6_1 => true,
            self::LEVEL_6_2 => true,
        ][$value]);
    }
}
