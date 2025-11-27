<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AudioCodec;

/**
 * Settings related to audio encoding. The settings in this group vary depending on the value that you choose for your
 * audio codec.
 */
final class AudioCodecSettings
{
    /**
     * Required when you set Codec to the value AAC. The service accepts one of two mutually exclusive groups of AAC
     * settings--VBR and CBR. To select one of these modes, set the value of Bitrate control mode to "VBR" or "CBR". In VBR
     * mode, you control the audio quality with the setting VBR quality. In CBR mode, you use the setting Bitrate. Defaults
     * and valid values depend on the rate control mode.
     *
     * @var AacSettings|null
     */
    private $aacSettings;

    /**
     * Required when you set Codec to the value AC3.
     *
     * @var Ac3Settings|null
     */
    private $ac3Settings;

    /**
     * Required when you set Codec to the value AIFF.
     *
     * @var AiffSettings|null
     */
    private $aiffSettings;

    /**
     * Choose the audio codec for this output. Note that the option Dolby Digital passthrough applies only to Dolby Digital
     * and Dolby Digital Plus audio inputs. Make sure that you choose a codec that's supported with your output container:
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/reference-codecs-containers.html#reference-codecs-containers-output-audio
     * For audio-only outputs, make sure that both your input audio codec and your output audio codec are supported for
     * audio-only workflows. For more information, see:
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/reference-codecs-containers-input.html#reference-codecs-containers-input-audio-only
     * and https://docs.aws.amazon.com/mediaconvert/latest/ug/reference-codecs-containers.html#audio-only-output.
     *
     * @var AudioCodec::*|null
     */
    private $codec;

    /**
     * Required when you set Codec to the value EAC3_ATMOS.
     *
     * @var Eac3AtmosSettings|null
     */
    private $eac3AtmosSettings;

    /**
     * Required when you set Codec to the value EAC3.
     *
     * @var Eac3Settings|null
     */
    private $eac3Settings;

    /**
     * Required when you set Codec, under AudioDescriptions>CodecSettings, to the value FLAC.
     *
     * @var FlacSettings|null
     */
    private $flacSettings;

    /**
     * Required when you set Codec to the value MP2.
     *
     * @var Mp2Settings|null
     */
    private $mp2Settings;

    /**
     * Required when you set Codec, under AudioDescriptions>CodecSettings, to the value MP3.
     *
     * @var Mp3Settings|null
     */
    private $mp3Settings;

    /**
     * Required when you set Codec, under AudioDescriptions>CodecSettings, to the value OPUS.
     *
     * @var OpusSettings|null
     */
    private $opusSettings;

    /**
     * Required when you set Codec, under AudioDescriptions>CodecSettings, to the value Vorbis.
     *
     * @var VorbisSettings|null
     */
    private $vorbisSettings;

    /**
     * Required when you set Codec to the value WAV.
     *
     * @var WavSettings|null
     */
    private $wavSettings;

    /**
     * @param array{
     *   AacSettings?: AacSettings|array|null,
     *   Ac3Settings?: Ac3Settings|array|null,
     *   AiffSettings?: AiffSettings|array|null,
     *   Codec?: AudioCodec::*|null,
     *   Eac3AtmosSettings?: Eac3AtmosSettings|array|null,
     *   Eac3Settings?: Eac3Settings|array|null,
     *   FlacSettings?: FlacSettings|array|null,
     *   Mp2Settings?: Mp2Settings|array|null,
     *   Mp3Settings?: Mp3Settings|array|null,
     *   OpusSettings?: OpusSettings|array|null,
     *   VorbisSettings?: VorbisSettings|array|null,
     *   WavSettings?: WavSettings|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->aacSettings = isset($input['AacSettings']) ? AacSettings::create($input['AacSettings']) : null;
        $this->ac3Settings = isset($input['Ac3Settings']) ? Ac3Settings::create($input['Ac3Settings']) : null;
        $this->aiffSettings = isset($input['AiffSettings']) ? AiffSettings::create($input['AiffSettings']) : null;
        $this->codec = $input['Codec'] ?? null;
        $this->eac3AtmosSettings = isset($input['Eac3AtmosSettings']) ? Eac3AtmosSettings::create($input['Eac3AtmosSettings']) : null;
        $this->eac3Settings = isset($input['Eac3Settings']) ? Eac3Settings::create($input['Eac3Settings']) : null;
        $this->flacSettings = isset($input['FlacSettings']) ? FlacSettings::create($input['FlacSettings']) : null;
        $this->mp2Settings = isset($input['Mp2Settings']) ? Mp2Settings::create($input['Mp2Settings']) : null;
        $this->mp3Settings = isset($input['Mp3Settings']) ? Mp3Settings::create($input['Mp3Settings']) : null;
        $this->opusSettings = isset($input['OpusSettings']) ? OpusSettings::create($input['OpusSettings']) : null;
        $this->vorbisSettings = isset($input['VorbisSettings']) ? VorbisSettings::create($input['VorbisSettings']) : null;
        $this->wavSettings = isset($input['WavSettings']) ? WavSettings::create($input['WavSettings']) : null;
    }

    /**
     * @param array{
     *   AacSettings?: AacSettings|array|null,
     *   Ac3Settings?: Ac3Settings|array|null,
     *   AiffSettings?: AiffSettings|array|null,
     *   Codec?: AudioCodec::*|null,
     *   Eac3AtmosSettings?: Eac3AtmosSettings|array|null,
     *   Eac3Settings?: Eac3Settings|array|null,
     *   FlacSettings?: FlacSettings|array|null,
     *   Mp2Settings?: Mp2Settings|array|null,
     *   Mp3Settings?: Mp3Settings|array|null,
     *   OpusSettings?: OpusSettings|array|null,
     *   VorbisSettings?: VorbisSettings|array|null,
     *   WavSettings?: WavSettings|array|null,
     * }|AudioCodecSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAacSettings(): ?AacSettings
    {
        return $this->aacSettings;
    }

    public function getAc3Settings(): ?Ac3Settings
    {
        return $this->ac3Settings;
    }

    public function getAiffSettings(): ?AiffSettings
    {
        return $this->aiffSettings;
    }

    /**
     * @return AudioCodec::*|null
     */
    public function getCodec(): ?string
    {
        return $this->codec;
    }

    public function getEac3AtmosSettings(): ?Eac3AtmosSettings
    {
        return $this->eac3AtmosSettings;
    }

    public function getEac3Settings(): ?Eac3Settings
    {
        return $this->eac3Settings;
    }

    public function getFlacSettings(): ?FlacSettings
    {
        return $this->flacSettings;
    }

    public function getMp2Settings(): ?Mp2Settings
    {
        return $this->mp2Settings;
    }

    public function getMp3Settings(): ?Mp3Settings
    {
        return $this->mp3Settings;
    }

    public function getOpusSettings(): ?OpusSettings
    {
        return $this->opusSettings;
    }

    public function getVorbisSettings(): ?VorbisSettings
    {
        return $this->vorbisSettings;
    }

    public function getWavSettings(): ?WavSettings
    {
        return $this->wavSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->aacSettings) {
            $payload['aacSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->ac3Settings) {
            $payload['ac3Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->aiffSettings) {
            $payload['aiffSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->codec) {
            if (!AudioCodec::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "codec" for "%s". The value "%s" is not a valid "AudioCodec".', __CLASS__, $v));
            }
            $payload['codec'] = $v;
        }
        if (null !== $v = $this->eac3AtmosSettings) {
            $payload['eac3AtmosSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->eac3Settings) {
            $payload['eac3Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->flacSettings) {
            $payload['flacSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->mp2Settings) {
            $payload['mp2Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->mp3Settings) {
            $payload['mp3Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->opusSettings) {
            $payload['opusSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->vorbisSettings) {
            $payload['vorbisSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->wavSettings) {
            $payload['wavSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
