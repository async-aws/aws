<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the bitstream mode for the AC-4 stream that the encoder emits. For more information about the AC-4 bitstream
 * mode, see ETSI TS 103 190. Maps to dlb_paec_ac4_bed_classifier in the encoder implementation. - COMPLETE_MAIN:
 * Complete Main (standard mix) - EMERGENCY: Stereo Emergency content.
 */
final class Ac4BitstreamMode
{
    public const COMPLETE_MAIN = 'COMPLETE_MAIN';
    public const EMERGENCY = 'EMERGENCY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COMPLETE_MAIN => true,
            self::EMERGENCY => true,
        ][$value]);
    }
}
