<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The Coding mode that you specify determines the number of audio channels and the audio channel layout metadata in
 * your AAC output. Valid coding modes depend on the Rate control mode and Profile that you select. The following list
 * shows the number of audio channels and channel layout for each coding mode. * 1.0 Audio Description (Receiver Mix):
 * One channel, C. Includes audio description data from your stereo input. For more information see ETSI TS 101 154
 * Annex E. * 1.0 Mono: One channel, C. * 2.0 Stereo: Two channels, L, R. * 5.1 Surround: Six channels, C, L, R, Ls, Rs,
 * LFE.
 */
final class AacCodingMode
{
    public const AD_RECEIVER_MIX = 'AD_RECEIVER_MIX';
    public const CODING_MODE_1_0 = 'CODING_MODE_1_0';
    public const CODING_MODE_1_1 = 'CODING_MODE_1_1';
    public const CODING_MODE_2_0 = 'CODING_MODE_2_0';
    public const CODING_MODE_5_1 = 'CODING_MODE_5_1';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AD_RECEIVER_MIX => true,
            self::CODING_MODE_1_0 => true,
            self::CODING_MODE_1_1 => true,
            self::CODING_MODE_2_0 => true,
            self::CODING_MODE_5_1 => true,
        ][$value]);
    }
}
