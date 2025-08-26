<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AacAudioDescriptionBroadcasterMix;
use AsyncAws\MediaConvert\Enum\AacCodecProfile;
use AsyncAws\MediaConvert\Enum\AacCodingMode;
use AsyncAws\MediaConvert\Enum\AacLoudnessMeasurementMode;
use AsyncAws\MediaConvert\Enum\AacRateControlMode;
use AsyncAws\MediaConvert\Enum\AacRawFormat;
use AsyncAws\MediaConvert\Enum\AacSpecification;
use AsyncAws\MediaConvert\Enum\AacVbrQuality;

/**
 * Required when you set Codec to the value AAC. The service accepts one of two mutually exclusive groups of AAC
 * settings--VBR and CBR. To select one of these modes, set the value of Bitrate control mode to "VBR" or "CBR". In VBR
 * mode, you control the audio quality with the setting VBR quality. In CBR mode, you use the setting Bitrate. Defaults
 * and valid values depend on the rate control mode.
 */
final class AacSettings
{
    /**
     * Choose BROADCASTER_MIXED_AD when the input contains pre-mixed main audio + audio description (AD) as a stereo pair.
     * The value for AudioType will be set to 3, which signals to downstream systems that this stream contains "broadcaster
     * mixed AD". Note that the input received by the encoder must contain pre-mixed audio; the encoder does not perform the
     * mixing. When you choose BROADCASTER_MIXED_AD, the encoder ignores any values you provide in AudioType and
     * FollowInputAudioType. Choose NORMAL when the input does not contain pre-mixed audio + audio description (AD). In this
     * case, the encoder will use any values you provide for AudioType and FollowInputAudioType.
     *
     * @var AacAudioDescriptionBroadcasterMix::*|null
     */
    private $audioDescriptionBroadcasterMix;

    /**
     * Specify the average bitrate in bits per second. The set of valid values for this setting is: 6000, 8000, 10000,
     * 12000, 14000, 16000, 20000, 24000, 28000, 32000, 40000, 48000, 56000, 64000, 80000, 96000, 112000, 128000, 160000,
     * 192000, 224000, 256000, 288000, 320000, 384000, 448000, 512000, 576000, 640000, 768000, 896000, 1024000. The value
     * you set is also constrained by the values that you choose for Profile, Bitrate control mode, and Sample rate. Default
     * values depend on Bitrate control mode and Profile.
     *
     * @var int|null
     */
    private $bitrate;

    /**
     * Specify the AAC profile. For the widest player compatibility and where higher bitrates are acceptable: Keep the
     * default profile, LC (AAC-LC) For improved audio performance at lower bitrates: Choose HEV1 or HEV2. HEV1 (AAC-HE v1)
     * adds spectral band replication to improve speech audio at low bitrates. HEV2 (AAC-HE v2) adds parametric stereo,
     * which optimizes for encoding stereo audio at very low bitrates. For improved audio quality at lower bitrates,
     * adaptive audio bitrate switching, and loudness control: Choose XHE.
     *
     * @var AacCodecProfile::*|null
     */
    private $codecProfile;

    /**
     * The Coding mode that you specify determines the number of audio channels and the audio channel layout metadata in
     * your AAC output. Valid coding modes depend on the Rate control mode and Profile that you select. The following list
     * shows the number of audio channels and channel layout for each coding mode. * 1.0 Audio Description (Receiver Mix):
     * One channel, C. Includes audio description data from your stereo input. For more information see ETSI TS 101 154
     * Annex E. * 1.0 Mono: One channel, C. * 2.0 Stereo: Two channels, L, R. * 5.1 Surround: Six channels, C, L, R, Ls, Rs,
     * LFE.
     *
     * @var AacCodingMode::*|null
     */
    private $codingMode;

    /**
     * Choose the loudness measurement mode for your audio content. For music or advertisements: We recommend that you keep
     * the default value, Program. For speech or other content: We recommend that you choose Anchor. When you do,
     * MediaConvert optimizes the loudness of your output for clarify by applying speech gates.
     *
     * @var AacLoudnessMeasurementMode::*|null
     */
    private $loudnessMeasurementMode;

    /**
     * Specify the RAP (Random Access Point) interval for your xHE-AAC audio output. A RAP allows a decoder to decode audio
     * data mid-stream, without the need to reference previous audio frames, and perform adaptive audio bitrate switching.
     * To specify the RAP interval: Enter an integer from 2000 to 30000, in milliseconds. Smaller values allow for better
     * seeking and more frequent stream switching, while large values improve compression efficiency. To have MediaConvert
     * automatically determine the RAP interval: Leave blank.
     *
     * @var int|null
     */
    private $rapInterval;

    /**
     * Specify the AAC rate control mode. For a constant bitrate: Choose CBR. Your AAC output bitrate will be equal to the
     * value that you choose for Bitrate. For a variable bitrate: Choose VBR. Your AAC output bitrate will vary according to
     * your audio content and the value that you choose for Bitrate quality.
     *
     * @var AacRateControlMode::*|null
     */
    private $rateControlMode;

    /**
     * Enables LATM/LOAS AAC output. Note that if you use LATM/LOAS AAC in an output, you must choose "No container" for the
     * output container.
     *
     * @var AacRawFormat::*|null
     */
    private $rawFormat;

    /**
     * Specify the AAC sample rate in samples per second (Hz). Valid sample rates depend on the AAC profile and Coding mode
     * that you select. For a list of supported sample rates, see:
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/aac-support.html.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * Use MPEG-2 AAC instead of MPEG-4 AAC audio for raw or MPEG-2 Transport Stream containers.
     *
     * @var AacSpecification::*|null
     */
    private $specification;

    /**
     * Specify the xHE-AAC loudness target. Enter an integer from 6 to 16, representing "loudness units". For more
     * information, see the following specification: Supplementary information for R 128 EBU Tech 3342-2023.
     *
     * @var int|null
     */
    private $targetLoudnessRange;

    /**
     * Specify the quality of your variable bitrate (VBR) AAC audio. For a list of approximate VBR bitrates, see:
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/aac-support.html#aac_vbr
     *
     * @var AacVbrQuality::*|null
     */
    private $vbrQuality;

    /**
     * @param array{
     *   AudioDescriptionBroadcasterMix?: null|AacAudioDescriptionBroadcasterMix::*,
     *   Bitrate?: null|int,
     *   CodecProfile?: null|AacCodecProfile::*,
     *   CodingMode?: null|AacCodingMode::*,
     *   LoudnessMeasurementMode?: null|AacLoudnessMeasurementMode::*,
     *   RapInterval?: null|int,
     *   RateControlMode?: null|AacRateControlMode::*,
     *   RawFormat?: null|AacRawFormat::*,
     *   SampleRate?: null|int,
     *   Specification?: null|AacSpecification::*,
     *   TargetLoudnessRange?: null|int,
     *   VbrQuality?: null|AacVbrQuality::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioDescriptionBroadcasterMix = $input['AudioDescriptionBroadcasterMix'] ?? null;
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->codecProfile = $input['CodecProfile'] ?? null;
        $this->codingMode = $input['CodingMode'] ?? null;
        $this->loudnessMeasurementMode = $input['LoudnessMeasurementMode'] ?? null;
        $this->rapInterval = $input['RapInterval'] ?? null;
        $this->rateControlMode = $input['RateControlMode'] ?? null;
        $this->rawFormat = $input['RawFormat'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
        $this->specification = $input['Specification'] ?? null;
        $this->targetLoudnessRange = $input['TargetLoudnessRange'] ?? null;
        $this->vbrQuality = $input['VbrQuality'] ?? null;
    }

    /**
     * @param array{
     *   AudioDescriptionBroadcasterMix?: null|AacAudioDescriptionBroadcasterMix::*,
     *   Bitrate?: null|int,
     *   CodecProfile?: null|AacCodecProfile::*,
     *   CodingMode?: null|AacCodingMode::*,
     *   LoudnessMeasurementMode?: null|AacLoudnessMeasurementMode::*,
     *   RapInterval?: null|int,
     *   RateControlMode?: null|AacRateControlMode::*,
     *   RawFormat?: null|AacRawFormat::*,
     *   SampleRate?: null|int,
     *   Specification?: null|AacSpecification::*,
     *   TargetLoudnessRange?: null|int,
     *   VbrQuality?: null|AacVbrQuality::*,
     * }|AacSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AacAudioDescriptionBroadcasterMix::*|null
     */
    public function getAudioDescriptionBroadcasterMix(): ?string
    {
        return $this->audioDescriptionBroadcasterMix;
    }

    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    /**
     * @return AacCodecProfile::*|null
     */
    public function getCodecProfile(): ?string
    {
        return $this->codecProfile;
    }

    /**
     * @return AacCodingMode::*|null
     */
    public function getCodingMode(): ?string
    {
        return $this->codingMode;
    }

    /**
     * @return AacLoudnessMeasurementMode::*|null
     */
    public function getLoudnessMeasurementMode(): ?string
    {
        return $this->loudnessMeasurementMode;
    }

    public function getRapInterval(): ?int
    {
        return $this->rapInterval;
    }

    /**
     * @return AacRateControlMode::*|null
     */
    public function getRateControlMode(): ?string
    {
        return $this->rateControlMode;
    }

    /**
     * @return AacRawFormat::*|null
     */
    public function getRawFormat(): ?string
    {
        return $this->rawFormat;
    }

    public function getSampleRate(): ?int
    {
        return $this->sampleRate;
    }

    /**
     * @return AacSpecification::*|null
     */
    public function getSpecification(): ?string
    {
        return $this->specification;
    }

    public function getTargetLoudnessRange(): ?int
    {
        return $this->targetLoudnessRange;
    }

    /**
     * @return AacVbrQuality::*|null
     */
    public function getVbrQuality(): ?string
    {
        return $this->vbrQuality;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioDescriptionBroadcasterMix) {
            if (!AacAudioDescriptionBroadcasterMix::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDescriptionBroadcasterMix" for "%s". The value "%s" is not a valid "AacAudioDescriptionBroadcasterMix".', __CLASS__, $v));
            }
            $payload['audioDescriptionBroadcasterMix'] = $v;
        }
        if (null !== $v = $this->bitrate) {
            $payload['bitrate'] = $v;
        }
        if (null !== $v = $this->codecProfile) {
            if (!AacCodecProfile::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "codecProfile" for "%s". The value "%s" is not a valid "AacCodecProfile".', __CLASS__, $v));
            }
            $payload['codecProfile'] = $v;
        }
        if (null !== $v = $this->codingMode) {
            if (!AacCodingMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "codingMode" for "%s". The value "%s" is not a valid "AacCodingMode".', __CLASS__, $v));
            }
            $payload['codingMode'] = $v;
        }
        if (null !== $v = $this->loudnessMeasurementMode) {
            if (!AacLoudnessMeasurementMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "loudnessMeasurementMode" for "%s". The value "%s" is not a valid "AacLoudnessMeasurementMode".', __CLASS__, $v));
            }
            $payload['loudnessMeasurementMode'] = $v;
        }
        if (null !== $v = $this->rapInterval) {
            $payload['rapInterval'] = $v;
        }
        if (null !== $v = $this->rateControlMode) {
            if (!AacRateControlMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "rateControlMode" for "%s". The value "%s" is not a valid "AacRateControlMode".', __CLASS__, $v));
            }
            $payload['rateControlMode'] = $v;
        }
        if (null !== $v = $this->rawFormat) {
            if (!AacRawFormat::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "rawFormat" for "%s". The value "%s" is not a valid "AacRawFormat".', __CLASS__, $v));
            }
            $payload['rawFormat'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }
        if (null !== $v = $this->specification) {
            if (!AacSpecification::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "specification" for "%s". The value "%s" is not a valid "AacSpecification".', __CLASS__, $v));
            }
            $payload['specification'] = $v;
        }
        if (null !== $v = $this->targetLoudnessRange) {
            $payload['targetLoudnessRange'] = $v;
        }
        if (null !== $v = $this->vbrQuality) {
            if (!AacVbrQuality::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "vbrQuality" for "%s". The value "%s" is not a valid "AacVbrQuality".', __CLASS__, $v));
            }
            $payload['vbrQuality'] = $v;
        }

        return $payload;
    }
}
