<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how MediaConvert selects audio content within your input. The default is Track. PID: Select audio by
 * specifying the Packet Identifier (PID) values for MPEG Transport Stream inputs. Use this when you know the exact PID
 * values of your audio streams. Track: Default. Select audio by track number. This is the most common option and works
 * with most input container formats. If more types of audio data get recognized in the future, these numberings may
 * shift, but the numberings used for Stream mode will not. Language code: Select audio by language using an ISO 639-2
 * or ISO 639-3 three-letter code in all capital letters. Use this when your source has embedded language metadata and
 * you want to select tracks based on their language. HLS rendition group: Select audio from an HLS rendition group. Use
 * this when your input is an HLS package with multiple audio renditions and you want to select specific rendition
 * groups. All PCM: Select all uncompressed PCM audio tracks from your input automatically. This is useful when you want
 * to include all PCM audio tracks without specifying individual track numbers. Stream: Select audio by stream number.
 * Stream numbers include all tracks in the source file, regardless of type, and correspond to either the order of
 * tracks in the file, or if applicable, the stream number metadata of the track. Although all tracks count toward these
 * stream numbers, in this audio selector context, only the stream number of a track containing audio data may be used.
 * If your source file contains a track which is not recognized by the service, then the corresponding stream number
 * will still be reserved for future use. If more types of audio data get recognized in the future, these numberings
 * will not shift.
 */
final class AudioSelectorType
{
    public const ALL_PCM = 'ALL_PCM';
    public const HLS_RENDITION_GROUP = 'HLS_RENDITION_GROUP';
    public const LANGUAGE_CODE = 'LANGUAGE_CODE';
    public const PID = 'PID';
    public const STREAM = 'STREAM';
    public const TRACK = 'TRACK';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALL_PCM => true,
            self::HLS_RENDITION_GROUP => true,
            self::LANGUAGE_CODE => true,
            self::PID => true,
            self::STREAM => true,
            self::TRACK => true,
        ][$value]);
    }
}
