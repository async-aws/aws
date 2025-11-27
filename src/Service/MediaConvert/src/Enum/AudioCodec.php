<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the audio codec for this output. Note that the option Dolby Digital passthrough applies only to Dolby Digital
 * and Dolby Digital Plus audio inputs. Make sure that you choose a codec that's supported with your output container:
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/reference-codecs-containers.html#reference-codecs-containers-output-audio
 * For audio-only outputs, make sure that both your input audio codec and your output audio codec are supported for
 * audio-only workflows. For more information, see:
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/reference-codecs-containers-input.html#reference-codecs-containers-input-audio-only
 * and https://docs.aws.amazon.com/mediaconvert/latest/ug/reference-codecs-containers.html#audio-only-output.
 */
final class AudioCodec
{
    public const AAC = 'AAC';
    public const AC3 = 'AC3';
    public const AIFF = 'AIFF';
    public const EAC3 = 'EAC3';
    public const EAC3_ATMOS = 'EAC3_ATMOS';
    public const FLAC = 'FLAC';
    public const MP2 = 'MP2';
    public const MP3 = 'MP3';
    public const OPUS = 'OPUS';
    public const PASSTHROUGH = 'PASSTHROUGH';
    public const VORBIS = 'VORBIS';
    public const WAV = 'WAV';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AAC => true,
            self::AC3 => true,
            self::AIFF => true,
            self::EAC3 => true,
            self::EAC3_ATMOS => true,
            self::FLAC => true,
            self::MP2 => true,
            self::MP3 => true,
            self::OPUS => true,
            self::PASSTHROUGH => true,
            self::VORBIS => true,
            self::WAV => true,
        ][$value]);
    }
}
