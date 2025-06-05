<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how MediaConvert selects audio content within your input. The default is Track. PID: Select audio by
 * specifying the Packet Identifier (PID) values for MPEG Transport Stream inputs. Use this when you know the exact PID
 * values of your audio streams. Track: Default. Select audio by track number. This is the most common option and works
 * with most input container formats. Language code: Select audio by language using ISO 639-2 or ISO 639-3 three-letter
 * language codes. Use this when your source has embedded language metadata and you want to select tracks based on their
 * language. HLS rendition group: Select audio from an HLS rendition group. Use this when your input is an HLS package
 * with multiple audio renditions and you want to select specific rendition groups. All PCM: Select all uncompressed PCM
 * audio tracks from your input automatically. This is useful when you want to include all PCM audio tracks without
 * specifying individual track numbers.
 */
final class AudioSelectorType
{
    public const ALL_PCM = 'ALL_PCM';
    public const HLS_RENDITION_GROUP = 'HLS_RENDITION_GROUP';
    public const LANGUAGE_CODE = 'LANGUAGE_CODE';
    public const PID = 'PID';
    public const TRACK = 'TRACK';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_PCM => true,
            self::HLS_RENDITION_GROUP => true,
            self::LANGUAGE_CODE => true,
            self::PID => true,
            self::TRACK => true,
        ][$value]);
    }
}
