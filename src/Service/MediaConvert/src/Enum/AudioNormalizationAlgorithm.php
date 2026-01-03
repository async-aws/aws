<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose one of the following audio normalization algorithms: ITU-R BS.1770-1: Ungated loudness. A measurement of
 * ungated average loudness for an entire piece of content, suitable for measurement of short-form content under ATSC
 * recommendation A/85. Supports up to 5.1 audio channels. ITU-R BS.1770-2: Gated loudness. A measurement of gated
 * average loudness compliant with the requirements of EBU-R128. Supports up to 5.1 audio channels. ITU-R BS.1770-3:
 * Modified peak. The same loudness measurement algorithm as 1770-2, with an updated true peak measurement. ITU-R
 * BS.1770-4: Higher channel count. Allows for more audio channels than the other algorithms, including configurations
 * such as 7.1.
 */
final class AudioNormalizationAlgorithm
{
    public const ITU_BS_1770_1 = 'ITU_BS_1770_1';
    public const ITU_BS_1770_2 = 'ITU_BS_1770_2';
    public const ITU_BS_1770_3 = 'ITU_BS_1770_3';
    public const ITU_BS_1770_4 = 'ITU_BS_1770_4';
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
        ][$value]);
    }
}
