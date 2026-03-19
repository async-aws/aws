<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Ac4BitstreamMode;
use AsyncAws\MediaConvert\Enum\Ac4CodingMode;
use AsyncAws\MediaConvert\Enum\Ac4DynamicRangeCompressionDrcProfile;
use AsyncAws\MediaConvert\Enum\Ac4StereoDownmix;

/**
 * Required when you set Codec to the value AC4.
 */
final class Ac4Settings
{
    /**
     * Specify the average bitrate in bits per second. Leave blank to use the default bitrate for the coding mode you select
     * according to ETSI TS 103 190. Valid bitrates for coding mode 2.0 (stereo): 48000, 64000, 96000, 128000, 144000,
     * 192000, 256000, 288000, 320000, 384000, 448000, 512000, or 768000. Valid bitrates for coding mode 5.1 (3/2 with LFE):
     * 96000, 128000, 144000, 192000, 256000, 288000, 320000, 384000, 448000, 512000, or 768000. Valid bitrates for coding
     * mode 5.1.4 (immersive): 192000, 256000, 288000, 320000, 384000, 448000, 512000, or 768000.
     *
     * @var int|null
     */
    private $bitrate;

    /**
     * Specify the bitstream mode for the AC-4 stream that the encoder emits. For more information about the AC-4 bitstream
     * mode, see ETSI TS 103 190. Maps to dlb_paec_ac4_bed_classifier in the encoder implementation. - COMPLETE_MAIN:
     * Complete Main (standard mix) - EMERGENCY: Stereo Emergency content.
     *
     * @var Ac4BitstreamMode::*|null
     */
    private $bitstreamMode;

    /**
     * Dolby AC-4 coding mode. Determines number of channels. Maps to dlb_paec_ac4_bed_channel_config in the encoder
     * implementation. - CODING_MODE_2_0: 2.0 (stereo) - maps to DLB_PAEC_AC4_BED_CHANNEL_CONFIG_20  - CODING_MODE_3_2_LFE:
     * 5.1 surround - maps to DLB_PAEC_AC4_BED_CHANNEL_CONFIG_51 - CODING_MODE_5_1_4: 5.1.4 immersive - maps to
     * DLB_PAEC_AC4_BED_CHANNEL_CONFIG_514.
     *
     * @var Ac4CodingMode::*|null
     */
    private $codingMode;

    /**
     * Choose the Dolby AC-4 dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the
     * Dolby AC-4 stream for the specified decoder mode. For information about the Dolby AC-4 DRC profiles, see the Dolby
     * AC-4 specification.
     *
     * @var Ac4DynamicRangeCompressionDrcProfile::*|null
     */
    private $dynamicRangeCompressionFlatPanelTv;

    /**
     * Choose the Dolby AC-4 dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the
     * Dolby AC-4 stream for the specified decoder mode. For information about the Dolby AC-4 DRC profiles, see the Dolby
     * AC-4 specification.
     *
     * @var Ac4DynamicRangeCompressionDrcProfile::*|null
     */
    private $dynamicRangeCompressionHomeTheater;

    /**
     * Choose the Dolby AC-4 dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the
     * Dolby AC-4 stream for the specified decoder mode. For information about the Dolby AC-4 DRC profiles, see the Dolby
     * AC-4 specification.
     *
     * @var Ac4DynamicRangeCompressionDrcProfile::*|null
     */
    private $dynamicRangeCompressionPortableHeadphones;

    /**
     * Choose the Dolby AC-4 dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the
     * Dolby AC-4 stream for the specified decoder mode. For information about the Dolby AC-4 DRC profiles, see the Dolby
     * AC-4 specification.
     *
     * @var Ac4DynamicRangeCompressionDrcProfile::*|null
     */
    private $dynamicRangeCompressionPortableSpeakers;

    /**
     * Specify a value for the following Dolby AC-4 setting: Left only/Right only center mix. MediaConvert uses this value
     * for downmixing. How the service uses this value depends on the value that you choose for Stereo downmix. Valid
     * values: 3.0, 1.5, 0.0, -1.5, -3.0, -4.5, -6.0, and -infinity. The value -infinity mutes the channel. This setting
     * applies only if you keep the default value of 3/2 - L, R, C, Ls, Rs for the setting Coding mode. If you choose a
     * different value for Coding mode, the service ignores Left only/Right only center.
     *
     * @var float|null
     */
    private $loRoCenterMixLevel;

    /**
     * Specify a value for the following Dolby AC-4 setting: Left only/Right only surround mix. MediaConvert uses this value
     * for downmixing. How the service uses this value depends on the value that you choose for Stereo downmix. Valid
     * values: -1.5, -3.0, -4.5, -6.0, and -infinity. The value -infinity mutes the channel. This setting applies only if
     * you keep the default value of 3/2 - L, R, C, Ls, Rs for the setting Coding mode. If you choose a different value for
     * Coding mode, the service ignores Left only/Right only surround.
     *
     * @var float|null
     */
    private $loRoSurroundMixLevel;

    /**
     * Specify a value for the following Dolby AC-4 setting: Left total/Right total center mix. MediaConvert uses this value
     * for downmixing. How the service uses this value depends on the value that you choose for Stereo downmix. Valid
     * values: 3.0, 1.5, 0.0, -1.5, -3.0, -4.5, -6.0, and -infinity. The value -infinity mutes the channel. This setting
     * applies only if you keep the default value of 3/2 - L, R, C, Ls, Rs for the setting Coding mode. If you choose a
     * different value for Coding mode, the service ignores Left total/Right total center.
     *
     * @var float|null
     */
    private $ltRtCenterMixLevel;

    /**
     * Specify a value for the following Dolby AC-4 setting: Left total/Right total surround mix. MediaConvert uses this
     * value for downmixing. How the service uses this value depends on the value that you choose for Stereo downmix. Valid
     * values: -1.5, -3.0, -4.5, -6.0, and -infinity. The value -infinity mutes the channel. This setting applies only if
     * you keep the default value of 3/2 - L, R, C, Ls, Rs for the setting Coding mode. If you choose a different value for
     * Coding mode, the service ignores Left total/Right total surround.
     *
     * @var float|null
     */
    private $ltRtSurroundMixLevel;

    /**
     * This value is always 48000. It represents the sample rate in Hz.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * Choose the preferred stereo downmix method. This setting tells the decoder how to downmix multi-channel audio to
     * stereo during playback.
     *
     * @var Ac4StereoDownmix::*|null
     */
    private $stereoDownmix;

    /**
     * @param array{
     *   Bitrate?: int|null,
     *   BitstreamMode?: Ac4BitstreamMode::*|null,
     *   CodingMode?: Ac4CodingMode::*|null,
     *   DynamicRangeCompressionFlatPanelTv?: Ac4DynamicRangeCompressionDrcProfile::*|null,
     *   DynamicRangeCompressionHomeTheater?: Ac4DynamicRangeCompressionDrcProfile::*|null,
     *   DynamicRangeCompressionPortableHeadphones?: Ac4DynamicRangeCompressionDrcProfile::*|null,
     *   DynamicRangeCompressionPortableSpeakers?: Ac4DynamicRangeCompressionDrcProfile::*|null,
     *   LoRoCenterMixLevel?: float|null,
     *   LoRoSurroundMixLevel?: float|null,
     *   LtRtCenterMixLevel?: float|null,
     *   LtRtSurroundMixLevel?: float|null,
     *   SampleRate?: int|null,
     *   StereoDownmix?: Ac4StereoDownmix::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->bitstreamMode = $input['BitstreamMode'] ?? null;
        $this->codingMode = $input['CodingMode'] ?? null;
        $this->dynamicRangeCompressionFlatPanelTv = $input['DynamicRangeCompressionFlatPanelTv'] ?? null;
        $this->dynamicRangeCompressionHomeTheater = $input['DynamicRangeCompressionHomeTheater'] ?? null;
        $this->dynamicRangeCompressionPortableHeadphones = $input['DynamicRangeCompressionPortableHeadphones'] ?? null;
        $this->dynamicRangeCompressionPortableSpeakers = $input['DynamicRangeCompressionPortableSpeakers'] ?? null;
        $this->loRoCenterMixLevel = $input['LoRoCenterMixLevel'] ?? null;
        $this->loRoSurroundMixLevel = $input['LoRoSurroundMixLevel'] ?? null;
        $this->ltRtCenterMixLevel = $input['LtRtCenterMixLevel'] ?? null;
        $this->ltRtSurroundMixLevel = $input['LtRtSurroundMixLevel'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
        $this->stereoDownmix = $input['StereoDownmix'] ?? null;
    }

    /**
     * @param array{
     *   Bitrate?: int|null,
     *   BitstreamMode?: Ac4BitstreamMode::*|null,
     *   CodingMode?: Ac4CodingMode::*|null,
     *   DynamicRangeCompressionFlatPanelTv?: Ac4DynamicRangeCompressionDrcProfile::*|null,
     *   DynamicRangeCompressionHomeTheater?: Ac4DynamicRangeCompressionDrcProfile::*|null,
     *   DynamicRangeCompressionPortableHeadphones?: Ac4DynamicRangeCompressionDrcProfile::*|null,
     *   DynamicRangeCompressionPortableSpeakers?: Ac4DynamicRangeCompressionDrcProfile::*|null,
     *   LoRoCenterMixLevel?: float|null,
     *   LoRoSurroundMixLevel?: float|null,
     *   LtRtCenterMixLevel?: float|null,
     *   LtRtSurroundMixLevel?: float|null,
     *   SampleRate?: int|null,
     *   StereoDownmix?: Ac4StereoDownmix::*|null,
     * }|Ac4Settings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    /**
     * @return Ac4BitstreamMode::*|null
     */
    public function getBitstreamMode(): ?string
    {
        return $this->bitstreamMode;
    }

    /**
     * @return Ac4CodingMode::*|null
     */
    public function getCodingMode(): ?string
    {
        return $this->codingMode;
    }

    /**
     * @return Ac4DynamicRangeCompressionDrcProfile::*|null
     */
    public function getDynamicRangeCompressionFlatPanelTv(): ?string
    {
        return $this->dynamicRangeCompressionFlatPanelTv;
    }

    /**
     * @return Ac4DynamicRangeCompressionDrcProfile::*|null
     */
    public function getDynamicRangeCompressionHomeTheater(): ?string
    {
        return $this->dynamicRangeCompressionHomeTheater;
    }

    /**
     * @return Ac4DynamicRangeCompressionDrcProfile::*|null
     */
    public function getDynamicRangeCompressionPortableHeadphones(): ?string
    {
        return $this->dynamicRangeCompressionPortableHeadphones;
    }

    /**
     * @return Ac4DynamicRangeCompressionDrcProfile::*|null
     */
    public function getDynamicRangeCompressionPortableSpeakers(): ?string
    {
        return $this->dynamicRangeCompressionPortableSpeakers;
    }

    public function getLoRoCenterMixLevel(): ?float
    {
        return $this->loRoCenterMixLevel;
    }

    public function getLoRoSurroundMixLevel(): ?float
    {
        return $this->loRoSurroundMixLevel;
    }

    public function getLtRtCenterMixLevel(): ?float
    {
        return $this->ltRtCenterMixLevel;
    }

    public function getLtRtSurroundMixLevel(): ?float
    {
        return $this->ltRtSurroundMixLevel;
    }

    public function getSampleRate(): ?int
    {
        return $this->sampleRate;
    }

    /**
     * @return Ac4StereoDownmix::*|null
     */
    public function getStereoDownmix(): ?string
    {
        return $this->stereoDownmix;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->bitrate) {
            $payload['bitrate'] = $v;
        }
        if (null !== $v = $this->bitstreamMode) {
            if (!Ac4BitstreamMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "bitstreamMode" for "%s". The value "%s" is not a valid "Ac4BitstreamMode".', __CLASS__, $v));
            }
            $payload['bitstreamMode'] = $v;
        }
        if (null !== $v = $this->codingMode) {
            if (!Ac4CodingMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "codingMode" for "%s". The value "%s" is not a valid "Ac4CodingMode".', __CLASS__, $v));
            }
            $payload['codingMode'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionFlatPanelTv) {
            if (!Ac4DynamicRangeCompressionDrcProfile::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicRangeCompressionFlatPanelTv" for "%s". The value "%s" is not a valid "Ac4DynamicRangeCompressionDrcProfile".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionFlatPanelTv'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionHomeTheater) {
            if (!Ac4DynamicRangeCompressionDrcProfile::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicRangeCompressionHomeTheater" for "%s". The value "%s" is not a valid "Ac4DynamicRangeCompressionDrcProfile".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionHomeTheater'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionPortableHeadphones) {
            if (!Ac4DynamicRangeCompressionDrcProfile::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicRangeCompressionPortableHeadphones" for "%s". The value "%s" is not a valid "Ac4DynamicRangeCompressionDrcProfile".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionPortableHeadphones'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionPortableSpeakers) {
            if (!Ac4DynamicRangeCompressionDrcProfile::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicRangeCompressionPortableSpeakers" for "%s". The value "%s" is not a valid "Ac4DynamicRangeCompressionDrcProfile".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionPortableSpeakers'] = $v;
        }
        if (null !== $v = $this->loRoCenterMixLevel) {
            $payload['loRoCenterMixLevel'] = $v;
        }
        if (null !== $v = $this->loRoSurroundMixLevel) {
            $payload['loRoSurroundMixLevel'] = $v;
        }
        if (null !== $v = $this->ltRtCenterMixLevel) {
            $payload['ltRtCenterMixLevel'] = $v;
        }
        if (null !== $v = $this->ltRtSurroundMixLevel) {
            $payload['ltRtSurroundMixLevel'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }
        if (null !== $v = $this->stereoDownmix) {
            if (!Ac4StereoDownmix::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "stereoDownmix" for "%s". The value "%s" is not a valid "Ac4StereoDownmix".', __CLASS__, $v));
            }
            $payload['stereoDownmix'] = $v;
        }

        return $payload;
    }
}
