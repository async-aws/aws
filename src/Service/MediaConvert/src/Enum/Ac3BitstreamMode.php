<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the bitstream mode for the AC-3 stream that the encoder emits. For more information about the AC3 bitstream
 * mode, see ATSC A/52-2012 (Annex E).
 */
final class Ac3BitstreamMode
{
    public const COMMENTARY = 'COMMENTARY';
    public const COMPLETE_MAIN = 'COMPLETE_MAIN';
    public const DIALOGUE = 'DIALOGUE';
    public const EMERGENCY = 'EMERGENCY';
    public const HEARING_IMPAIRED = 'HEARING_IMPAIRED';
    public const MUSIC_AND_EFFECTS = 'MUSIC_AND_EFFECTS';
    public const VISUALLY_IMPAIRED = 'VISUALLY_IMPAIRED';
    public const VOICE_OVER = 'VOICE_OVER';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COMMENTARY => true,
            self::COMPLETE_MAIN => true,
            self::DIALOGUE => true,
            self::EMERGENCY => true,
            self::HEARING_IMPAIRED => true,
            self::MUSIC_AND_EFFECTS => true,
            self::VISUALLY_IMPAIRED => true,
            self::VOICE_OVER => true,
        ][$value]);
    }
}
