<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * To create assets that have the same TIC values in each audio track, keep the default value Share TICs. To create
 * assets that have unique TIC values for each audio track, choose Use unique TICs.
 */
final class NielsenUniqueTicPerAudioTrackType
{
    public const RESERVE_UNIQUE_TICS_PER_TRACK = 'RESERVE_UNIQUE_TICS_PER_TRACK';
    public const SAME_TICS_PER_TRACK = 'SAME_TICS_PER_TRACK';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::RESERVE_UNIQUE_TICS_PER_TRACK => true,
            self::SAME_TICS_PER_TRACK => true,
        ][$value]);
    }
}
