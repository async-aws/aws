<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify this setting only when your output will be consumed by a downstream repackaging workflow that is sensitive to
 * very small duration differences between video and audio. For this situation, choose Match video duration. In all
 * other cases, keep the default value, Default codec duration. When you choose Match video duration, MediaConvert pads
 * the output audio streams with silence or trims them to ensure that the total duration of each audio stream is at
 * least as long as the total duration of the video stream. After padding or trimming, the audio stream duration is no
 * more than one frame longer than the video stream. MediaConvert applies audio padding or trimming only to the end of
 * the last segment of the output. For unsegmented outputs, MediaConvert adds padding only to the end of the file. When
 * you keep the default value, any minor discrepancies between audio and video duration will depend on your output audio
 * codec.
 */
final class M2tsAudioDuration
{
    public const DEFAULT_CODEC_DURATION = 'DEFAULT_CODEC_DURATION';
    public const MATCH_VIDEO_DURATION = 'MATCH_VIDEO_DURATION';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT_CODEC_DURATION => true,
            self::MATCH_VIDEO_DURATION => true,
        ][$value]);
    }
}
