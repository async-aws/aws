<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify an H.264 level that is consistent with your output video settings. If you aren't sure what level to specify,
 * choose Auto.
 */
final class H264CodecLevel
{
    public const AUTO = 'AUTO';
    public const LEVEL_1 = 'LEVEL_1';
    public const LEVEL_1_1 = 'LEVEL_1_1';
    public const LEVEL_1_2 = 'LEVEL_1_2';
    public const LEVEL_1_3 = 'LEVEL_1_3';
    public const LEVEL_2 = 'LEVEL_2';
    public const LEVEL_2_1 = 'LEVEL_2_1';
    public const LEVEL_2_2 = 'LEVEL_2_2';
    public const LEVEL_3 = 'LEVEL_3';
    public const LEVEL_3_1 = 'LEVEL_3_1';
    public const LEVEL_3_2 = 'LEVEL_3_2';
    public const LEVEL_4 = 'LEVEL_4';
    public const LEVEL_4_1 = 'LEVEL_4_1';
    public const LEVEL_4_2 = 'LEVEL_4_2';
    public const LEVEL_5 = 'LEVEL_5';
    public const LEVEL_5_1 = 'LEVEL_5_1';
    public const LEVEL_5_2 = 'LEVEL_5_2';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::LEVEL_1 => true,
            self::LEVEL_1_1 => true,
            self::LEVEL_1_2 => true,
            self::LEVEL_1_3 => true,
            self::LEVEL_2 => true,
            self::LEVEL_2_1 => true,
            self::LEVEL_2_2 => true,
            self::LEVEL_3 => true,
            self::LEVEL_3_1 => true,
            self::LEVEL_3_2 => true,
            self::LEVEL_4 => true,
            self::LEVEL_4_1 => true,
            self::LEVEL_4_2 => true,
            self::LEVEL_5 => true,
            self::LEVEL_5_1 => true,
            self::LEVEL_5_2 => true,
        ][$value]);
    }
}
