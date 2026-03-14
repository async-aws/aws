<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Dolby AC-4 coding mode. Determines number of channels. Maps to dlb_paec_ac4_bed_channel_config in the encoder
 * implementation. - CODING_MODE_2_0: 2.0 (stereo) - maps to DLB_PAEC_AC4_BED_CHANNEL_CONFIG_20  - CODING_MODE_3_2_LFE:
 * 5.1 surround - maps to DLB_PAEC_AC4_BED_CHANNEL_CONFIG_51 - CODING_MODE_5_1_4: 5.1.4 immersive - maps to
 * DLB_PAEC_AC4_BED_CHANNEL_CONFIG_514.
 */
final class Ac4CodingMode
{
    public const CODING_MODE_2_0 = 'CODING_MODE_2_0';
    public const CODING_MODE_3_2_LFE = 'CODING_MODE_3_2_LFE';
    public const CODING_MODE_5_1_4 = 'CODING_MODE_5_1_4';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CODING_MODE_2_0 => true,
            self::CODING_MODE_3_2_LFE => true,
            self::CODING_MODE_5_1_4 => true,
        ][$value]);
    }
}
