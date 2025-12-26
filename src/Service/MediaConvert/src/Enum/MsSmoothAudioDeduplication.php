<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * COMBINE_DUPLICATE_STREAMS combines identical audio encoding settings across a Microsoft Smooth output group into a
 * single audio stream.
 */
final class MsSmoothAudioDeduplication
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const COMBINE_DUPLICATE_STREAMS = 'COMBINE_DUPLICATE_STREAMS';
    public const NONE = 'NONE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COMBINE_DUPLICATE_STREAMS => true,
            self::NONE => true,
        ][$value]);
    }
}
