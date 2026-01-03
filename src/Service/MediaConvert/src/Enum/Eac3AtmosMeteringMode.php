<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose how the service meters the loudness of your audio.
 */
final class Eac3AtmosMeteringMode
{
    public const ITU_BS_1770_1 = 'ITU_BS_1770_1';
    public const ITU_BS_1770_2 = 'ITU_BS_1770_2';
    public const ITU_BS_1770_3 = 'ITU_BS_1770_3';
    public const ITU_BS_1770_4 = 'ITU_BS_1770_4';
    public const LEQ_A = 'LEQ_A';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ITU_BS_1770_1 => true,
            self::ITU_BS_1770_2 => true,
            self::ITU_BS_1770_3 => true,
            self::ITU_BS_1770_4 => true,
            self::LEQ_A => true,
        ][$value]);
    }
}
