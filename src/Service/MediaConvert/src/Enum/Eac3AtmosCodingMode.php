<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The coding mode for Dolby Digital Plus JOC (Atmos).
 */
final class Eac3AtmosCodingMode
{
    public const CODING_MODE_5_1_4 = 'CODING_MODE_5_1_4';
    public const CODING_MODE_7_1_4 = 'CODING_MODE_7_1_4';
    public const CODING_MODE_9_1_6 = 'CODING_MODE_9_1_6';
    public const CODING_MODE_AUTO = 'CODING_MODE_AUTO';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CODING_MODE_5_1_4 => true,
            self::CODING_MODE_7_1_4 => true,
            self::CODING_MODE_9_1_6 => true,
            self::CODING_MODE_AUTO => true,
        ][$value]);
    }
}
