<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Selects between the DVB and ATSC buffer models for Dolby Digital audio.
 */
final class M2tsAudioBufferModel
{
    public const ATSC = 'ATSC';
    public const DVB = 'DVB';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ATSC => true,
            self::DVB => true,
        ][$value]);
    }
}
