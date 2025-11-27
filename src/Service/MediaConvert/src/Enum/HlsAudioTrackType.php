<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Four types of audio-only tracks are supported: Audio-Only Variant Stream The client can play back this audio-only
 * stream instead of video in low-bandwidth scenarios. Represented as an EXT-X-STREAM-INF in the HLS manifest. Alternate
 * Audio, Auto Select, Default Alternate rendition that the client should try to play back by default. Represented as an
 * EXT-X-MEDIA in the HLS manifest with DEFAULT=YES, AUTOSELECT=YES Alternate Audio, Auto Select, Not Default Alternate
 * rendition that the client may try to play back by default. Represented as an EXT-X-MEDIA in the HLS manifest with
 * DEFAULT=NO, AUTOSELECT=YES Alternate Audio, not Auto Select Alternate rendition that the client will not try to play
 * back by default. Represented as an EXT-X-MEDIA in the HLS manifest with DEFAULT=NO, AUTOSELECT=NO.
 */
final class HlsAudioTrackType
{
    public const ALTERNATE_AUDIO_AUTO_SELECT = 'ALTERNATE_AUDIO_AUTO_SELECT';
    public const ALTERNATE_AUDIO_AUTO_SELECT_DEFAULT = 'ALTERNATE_AUDIO_AUTO_SELECT_DEFAULT';
    public const ALTERNATE_AUDIO_NOT_AUTO_SELECT = 'ALTERNATE_AUDIO_NOT_AUTO_SELECT';
    public const AUDIO_ONLY_VARIANT_STREAM = 'AUDIO_ONLY_VARIANT_STREAM';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALTERNATE_AUDIO_AUTO_SELECT => true,
            self::ALTERNATE_AUDIO_AUTO_SELECT_DEFAULT => true,
            self::ALTERNATE_AUDIO_NOT_AUTO_SELECT => true,
            self::AUDIO_ONLY_VARIANT_STREAM => true,
        ][$value]);
    }
}
