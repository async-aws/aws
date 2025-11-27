<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Dolby Digital coding mode. Determines number of channels.
 */
final class Ac3CodingMode
{
    public const CODING_MODE_1_0 = 'CODING_MODE_1_0';
    public const CODING_MODE_1_1 = 'CODING_MODE_1_1';
    public const CODING_MODE_2_0 = 'CODING_MODE_2_0';
    public const CODING_MODE_3_2_LFE = 'CODING_MODE_3_2_LFE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CODING_MODE_1_0 => true,
            self::CODING_MODE_1_1 => true,
            self::CODING_MODE_2_0 => true,
            self::CODING_MODE_3_2_LFE => true,
        ][$value]);
    }
}
