<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether your DVB subtitles are standard or for hearing impaired. Choose hearing impaired if your subtitles
 * include audio descriptions and dialogue. Choose standard if your subtitles include only dialogue.
 */
final class DvbSubtitlingType
{
    public const HEARING_IMPAIRED = 'HEARING_IMPAIRED';
    public const STANDARD = 'STANDARD';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HEARING_IMPAIRED => true,
            self::STANDARD => true,
        ][$value]);
    }
}
