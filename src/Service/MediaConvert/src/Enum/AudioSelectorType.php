<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specifies the type of the audio selector.
 */
final class AudioSelectorType
{
    public const HLS_RENDITION_GROUP = 'HLS_RENDITION_GROUP';
    public const LANGUAGE_CODE = 'LANGUAGE_CODE';
    public const PID = 'PID';
    public const TRACK = 'TRACK';

    public static function exists(string $value): bool
    {
        return isset([
            self::HLS_RENDITION_GROUP => true,
            self::LANGUAGE_CODE => true,
            self::PID => true,
            self::TRACK => true,
        ][$value]);
    }
}
