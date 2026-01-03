<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use this setting to control the values that MediaConvert puts in your HLS parent playlist to control how the client
 * player selects which audio track to play. Choose Audio-only variant stream (AUDIO_ONLY_VARIANT_STREAM) for any
 * variant that you want to prohibit the client from playing with video. This causes MediaConvert to represent the
 * variant as an EXT-X-STREAM-INF in the HLS manifest. The other options for this setting determine the values that
 * MediaConvert writes for the DEFAULT and AUTOSELECT attributes of the EXT-X-MEDIA entry for the audio variant. For
 * more information about these attributes, see the Apple documentation article
 * https://developer.apple.com/documentation/http_live_streaming/example_playlists_for_http_live_streaming/adding_alternate_media_to_a_playlist.
 * Choose Alternate audio, auto select, default to set DEFAULT=YES and AUTOSELECT=YES. Choose this value for only one
 * variant in your output group. Choose Alternate audio, auto select, not default to set DEFAULT=NO and AUTOSELECT=YES.
 * Choose Alternate Audio, Not Auto Select to set DEFAULT=NO and AUTOSELECT=NO. When you don't specify a value for this
 * setting, MediaConvert defaults to Alternate audio, auto select, default. When there is more than one variant in your
 * output group, you must explicitly choose a value for this setting.
 */
final class CmfcAudioTrackType
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
