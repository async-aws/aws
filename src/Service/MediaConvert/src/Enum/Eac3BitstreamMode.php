<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the bitstream mode for the E-AC-3 stream that the encoder emits. For more information about the EAC3
 * bitstream mode, see ATSC A/52-2012 (Annex E).
 */
final class Eac3BitstreamMode
{
    public const COMMENTARY = 'COMMENTARY';
    public const COMPLETE_MAIN = 'COMPLETE_MAIN';
    public const EMERGENCY = 'EMERGENCY';
    public const HEARING_IMPAIRED = 'HEARING_IMPAIRED';
    public const VISUALLY_IMPAIRED = 'VISUALLY_IMPAIRED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COMMENTARY => true,
            self::COMPLETE_MAIN => true,
            self::EMERGENCY => true,
            self::HEARING_IMPAIRED => true,
            self::VISUALLY_IMPAIRED => true,
        ][$value]);
    }
}
