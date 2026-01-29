<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Dolby Digital Plus coding mode. Determines number of channels.
 */
final class Eac3CodingMode
{
    public const CODING_MODE_1_0 = 'CODING_MODE_1_0';
    public const CODING_MODE_2_0 = 'CODING_MODE_2_0';
    public const CODING_MODE_3_2 = 'CODING_MODE_3_2';
    public const CODING_MODE_AUTO = 'CODING_MODE_AUTO';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CODING_MODE_1_0 => true,
            self::CODING_MODE_2_0 => true,
            self::CODING_MODE_3_2 => true,
            self::CODING_MODE_AUTO => true,
        ][$value]);
    }
}
