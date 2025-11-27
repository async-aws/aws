<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the bitstream mode for the E-AC-3 stream that the encoder emits. For more information about the EAC3
 * bitstream mode, see ATSC A/52-2012 (Annex E).
 */
final class Eac3AtmosBitstreamMode
{
    public const COMPLETE_MAIN = 'COMPLETE_MAIN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COMPLETE_MAIN => true,
        ][$value]);
    }
}
