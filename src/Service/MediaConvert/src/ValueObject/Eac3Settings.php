<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Eac3AttenuationControl;
use AsyncAws\MediaConvert\Enum\Eac3BitstreamMode;
use AsyncAws\MediaConvert\Enum\Eac3CodingMode;
use AsyncAws\MediaConvert\Enum\Eac3DcFilter;
use AsyncAws\MediaConvert\Enum\Eac3DynamicRangeCompressionLine;
use AsyncAws\MediaConvert\Enum\Eac3DynamicRangeCompressionRf;
use AsyncAws\MediaConvert\Enum\Eac3LfeControl;
use AsyncAws\MediaConvert\Enum\Eac3LfeFilter;
use AsyncAws\MediaConvert\Enum\Eac3MetadataControl;
use AsyncAws\MediaConvert\Enum\Eac3PassthroughControl;
use AsyncAws\MediaConvert\Enum\Eac3PhaseControl;
use AsyncAws\MediaConvert\Enum\Eac3StereoDownmix;
use AsyncAws\MediaConvert\Enum\Eac3SurroundExMode;
use AsyncAws\MediaConvert\Enum\Eac3SurroundMode;

/**
 * Required when you set Codec to the value EAC3.
 */
final class Eac3Settings
{
    /**
     * If set to ATTENUATE_3_DB, applies a 3 dB attenuation to the surround channels. Only used for 3/2 coding mode.
     *
     * @var Eac3AttenuationControl::*|null
     */
    private $attenuationControl;

    /**
     * Specify the average bitrate in bits per second. The bitrate that you specify must be a multiple of 8000 within the
     * allowed minimum and maximum values. Leave blank to use the default bitrate for the coding mode you select according
     * ETSI TS 102 366. Valid bitrates for coding mode 1/0: Default: 96000. Minimum: 32000. Maximum: 3024000. Valid bitrates
     * for coding mode 2/0: Default: 192000. Minimum: 96000. Maximum: 3024000. Valid bitrates for coding mode 3/2: Default:
     * 384000. Minimum: 192000. Maximum: 3024000.
     *
     * @var int|null
     */
    private $bitrate;

    /**
     * Specify the bitstream mode for the E-AC-3 stream that the encoder emits. For more information about the EAC3
     * bitstream mode, see ATSC A/52-2012 (Annex E).
     *
     * @var Eac3BitstreamMode::*|null
     */
    private $bitstreamMode;

    /**
     * Dolby Digital Plus coding mode. Determines number of channels.
     *
     * @var Eac3CodingMode::*|null
     */
    private $codingMode;

    /**
     * Activates a DC highpass filter for all input channels.
     *
     * @var Eac3DcFilter::*|null
     */
    private $dcFilter;

    /**
     * Sets the dialnorm for the output. If blank and input audio is Dolby Digital Plus, dialnorm will be passed through.
     *
     * @var int|null
     */
    private $dialnorm;

    /**
     * Choose the Dolby Digital dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the
     * Dolby Digital stream for the line operating mode. Related setting: When you use this setting, MediaConvert ignores
     * any value you provide for Dynamic range compression profile. For information about the Dolby Digital DRC operating
     * modes and profiles, see the Dynamic Range Control chapter of the Dolby Metadata Guide at
     * https://developer.dolby.com/globalassets/professional/documents/dolby-metadata-guide.pdf.
     *
     * @var Eac3DynamicRangeCompressionLine::*|null
     */
    private $dynamicRangeCompressionLine;

    /**
     * Choose the Dolby Digital dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the
     * Dolby Digital stream for the RF operating mode. Related setting: When you use this setting, MediaConvert ignores any
     * value you provide for Dynamic range compression profile. For information about the Dolby Digital DRC operating modes
     * and profiles, see the Dynamic Range Control chapter of the Dolby Metadata Guide at
     * https://developer.dolby.com/globalassets/professional/documents/dolby-metadata-guide.pdf.
     *
     * @var Eac3DynamicRangeCompressionRf::*|null
     */
    private $dynamicRangeCompressionRf;

    /**
     * When encoding 3/2 audio, controls whether the LFE channel is enabled.
     *
     * @var Eac3LfeControl::*|null
     */
    private $lfeControl;

    /**
     * Applies a 120Hz lowpass filter to the LFE channel prior to encoding. Only valid with 3_2_LFE coding mode.
     *
     * @var Eac3LfeFilter::*|null
     */
    private $lfeFilter;

    /**
     * Specify a value for the following Dolby Digital Plus setting: Left only/Right only center mix. MediaConvert uses this
     * value for downmixing. How the service uses this value depends on the value that you choose for Stereo downmix. Valid
     * values: 3.0, 1.5, 0.0, -1.5, -3.0, -4.5, -6.0, and -60. The value -60 mutes the channel. This setting applies only if
     * you keep the default value of 3/2 - L, R, C, Ls, Rs for the setting Coding mode. If you choose a different value for
     * Coding mode, the service ignores Left only/Right only center.
     *
     * @var float|null
     */
    private $loRoCenterMixLevel;

    /**
     * Specify a value for the following Dolby Digital Plus setting: Left only/Right only. MediaConvert uses this value for
     * downmixing. How the service uses this value depends on the value that you choose for Stereo downmix. Valid values:
     * -1.5, -3.0, -4.5, -6.0, and -60. The value -60 mutes the channel. This setting applies only if you keep the default
     * value of 3/2 - L, R, C, Ls, Rs for the setting Coding mode. If you choose a different value for Coding mode, the
     * service ignores Left only/Right only surround.
     *
     * @var float|null
     */
    private $loRoSurroundMixLevel;

    /**
     * Specify a value for the following Dolby Digital Plus setting: Left total/Right total center mix. MediaConvert uses
     * this value for downmixing. How the service uses this value depends on the value that you choose for Stereo downmix.
     * Valid values: 3.0, 1.5, 0.0, -1.5, -3.0, -4.5, -6.0, and -60. The value -60 mutes the channel. This setting applies
     * only if you keep the default value of 3/2 - L, R, C, Ls, Rs for the setting Coding mode. If you choose a different
     * value for Coding mode, the service ignores Left total/Right total center.
     *
     * @var float|null
     */
    private $ltRtCenterMixLevel;

    /**
     * Specify a value for the following Dolby Digital Plus setting: Left total/Right total surround mix. MediaConvert uses
     * this value for downmixing. How the service uses this value depends on the value that you choose for Stereo downmix.
     * Valid values: -1.5, -3.0, -4.5, -6.0, and -60. The value -60 mutes the channel. This setting applies only if you keep
     * the default value of 3/2 - L, R, C, Ls, Rs for the setting Coding mode. If you choose a different value for Coding
     * mode, the service ignores Left total/Right total surround.
     *
     * @var float|null
     */
    private $ltRtSurroundMixLevel;

    /**
     * When set to FOLLOW_INPUT, encoder metadata will be sourced from the DD, DD+, or DolbyE decoder that supplied this
     * audio data. If audio was not supplied from one of these streams, then the static metadata settings will be used.
     *
     * @var Eac3MetadataControl::*|null
     */
    private $metadataControl;

    /**
     * When set to WHEN_POSSIBLE, input DD+ audio will be passed through if it is present on the input. this detection is
     * dynamic over the life of the transcode. Inputs that alternate between DD+ and non-DD+ content will have a consistent
     * DD+ output as the system alternates between passthrough and encoding.
     *
     * @var Eac3PassthroughControl::*|null
     */
    private $passthroughControl;

    /**
     * Controls the amount of phase-shift applied to the surround channels. Only used for 3/2 coding mode.
     *
     * @var Eac3PhaseControl::*|null
     */
    private $phaseControl;

    /**
     * This value is always 48000. It represents the sample rate in Hz.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * Choose how the service does stereo downmixing. This setting only applies if you keep the default value of 3/2 - L, R,
     * C, Ls, Rs for the setting Coding mode. If you choose a different value for Coding mode, the service ignores Stereo
     * downmix.
     *
     * @var Eac3StereoDownmix::*|null
     */
    private $stereoDownmix;

    /**
     * When encoding 3/2 audio, sets whether an extra center back surround channel is matrix encoded into the left and right
     * surround channels.
     *
     * @var Eac3SurroundExMode::*|null
     */
    private $surroundExMode;

    /**
     * When encoding 2/0 audio, sets whether Dolby Surround is matrix encoded into the two channels.
     *
     * @var Eac3SurroundMode::*|null
     */
    private $surroundMode;

    /**
     * @param array{
     *   AttenuationControl?: Eac3AttenuationControl::*|null,
     *   Bitrate?: int|null,
     *   BitstreamMode?: Eac3BitstreamMode::*|null,
     *   CodingMode?: Eac3CodingMode::*|null,
     *   DcFilter?: Eac3DcFilter::*|null,
     *   Dialnorm?: int|null,
     *   DynamicRangeCompressionLine?: Eac3DynamicRangeCompressionLine::*|null,
     *   DynamicRangeCompressionRf?: Eac3DynamicRangeCompressionRf::*|null,
     *   LfeControl?: Eac3LfeControl::*|null,
     *   LfeFilter?: Eac3LfeFilter::*|null,
     *   LoRoCenterMixLevel?: float|null,
     *   LoRoSurroundMixLevel?: float|null,
     *   LtRtCenterMixLevel?: float|null,
     *   LtRtSurroundMixLevel?: float|null,
     *   MetadataControl?: Eac3MetadataControl::*|null,
     *   PassthroughControl?: Eac3PassthroughControl::*|null,
     *   PhaseControl?: Eac3PhaseControl::*|null,
     *   SampleRate?: int|null,
     *   StereoDownmix?: Eac3StereoDownmix::*|null,
     *   SurroundExMode?: Eac3SurroundExMode::*|null,
     *   SurroundMode?: Eac3SurroundMode::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->attenuationControl = $input['AttenuationControl'] ?? null;
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->bitstreamMode = $input['BitstreamMode'] ?? null;
        $this->codingMode = $input['CodingMode'] ?? null;
        $this->dcFilter = $input['DcFilter'] ?? null;
        $this->dialnorm = $input['Dialnorm'] ?? null;
        $this->dynamicRangeCompressionLine = $input['DynamicRangeCompressionLine'] ?? null;
        $this->dynamicRangeCompressionRf = $input['DynamicRangeCompressionRf'] ?? null;
        $this->lfeControl = $input['LfeControl'] ?? null;
        $this->lfeFilter = $input['LfeFilter'] ?? null;
        $this->loRoCenterMixLevel = $input['LoRoCenterMixLevel'] ?? null;
        $this->loRoSurroundMixLevel = $input['LoRoSurroundMixLevel'] ?? null;
        $this->ltRtCenterMixLevel = $input['LtRtCenterMixLevel'] ?? null;
        $this->ltRtSurroundMixLevel = $input['LtRtSurroundMixLevel'] ?? null;
        $this->metadataControl = $input['MetadataControl'] ?? null;
        $this->passthroughControl = $input['PassthroughControl'] ?? null;
        $this->phaseControl = $input['PhaseControl'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
        $this->stereoDownmix = $input['StereoDownmix'] ?? null;
        $this->surroundExMode = $input['SurroundExMode'] ?? null;
        $this->surroundMode = $input['SurroundMode'] ?? null;
    }

    /**
     * @param array{
     *   AttenuationControl?: Eac3AttenuationControl::*|null,
     *   Bitrate?: int|null,
     *   BitstreamMode?: Eac3BitstreamMode::*|null,
     *   CodingMode?: Eac3CodingMode::*|null,
     *   DcFilter?: Eac3DcFilter::*|null,
     *   Dialnorm?: int|null,
     *   DynamicRangeCompressionLine?: Eac3DynamicRangeCompressionLine::*|null,
     *   DynamicRangeCompressionRf?: Eac3DynamicRangeCompressionRf::*|null,
     *   LfeControl?: Eac3LfeControl::*|null,
     *   LfeFilter?: Eac3LfeFilter::*|null,
     *   LoRoCenterMixLevel?: float|null,
     *   LoRoSurroundMixLevel?: float|null,
     *   LtRtCenterMixLevel?: float|null,
     *   LtRtSurroundMixLevel?: float|null,
     *   MetadataControl?: Eac3MetadataControl::*|null,
     *   PassthroughControl?: Eac3PassthroughControl::*|null,
     *   PhaseControl?: Eac3PhaseControl::*|null,
     *   SampleRate?: int|null,
     *   StereoDownmix?: Eac3StereoDownmix::*|null,
     *   SurroundExMode?: Eac3SurroundExMode::*|null,
     *   SurroundMode?: Eac3SurroundMode::*|null,
     * }|Eac3Settings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Eac3AttenuationControl::*|null
     */
    public function getAttenuationControl(): ?string
    {
        return $this->attenuationControl;
    }

    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    /**
     * @return Eac3BitstreamMode::*|null
     */
    public function getBitstreamMode(): ?string
    {
        return $this->bitstreamMode;
    }

    /**
     * @return Eac3CodingMode::*|null
     */
    public function getCodingMode(): ?string
    {
        return $this->codingMode;
    }

    /**
     * @return Eac3DcFilter::*|null
     */
    public function getDcFilter(): ?string
    {
        return $this->dcFilter;
    }

    public function getDialnorm(): ?int
    {
        return $this->dialnorm;
    }

    /**
     * @return Eac3DynamicRangeCompressionLine::*|null
     */
    public function getDynamicRangeCompressionLine(): ?string
    {
        return $this->dynamicRangeCompressionLine;
    }

    /**
     * @return Eac3DynamicRangeCompressionRf::*|null
     */
    public function getDynamicRangeCompressionRf(): ?string
    {
        return $this->dynamicRangeCompressionRf;
    }

    /**
     * @return Eac3LfeControl::*|null
     */
    public function getLfeControl(): ?string
    {
        return $this->lfeControl;
    }

    /**
     * @return Eac3LfeFilter::*|null
     */
    public function getLfeFilter(): ?string
    {
        return $this->lfeFilter;
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

    /**
     * @return Eac3MetadataControl::*|null
     */
    public function getMetadataControl(): ?string
    {
        return $this->metadataControl;
    }

    /**
     * @return Eac3PassthroughControl::*|null
     */
    public function getPassthroughControl(): ?string
    {
        return $this->passthroughControl;
    }

    /**
     * @return Eac3PhaseControl::*|null
     */
    public function getPhaseControl(): ?string
    {
        return $this->phaseControl;
    }

    public function getSampleRate(): ?int
    {
        return $this->sampleRate;
    }

    /**
     * @return Eac3StereoDownmix::*|null
     */
    public function getStereoDownmix(): ?string
    {
        return $this->stereoDownmix;
    }

    /**
     * @return Eac3SurroundExMode::*|null
     */
    public function getSurroundExMode(): ?string
    {
        return $this->surroundExMode;
    }

    /**
     * @return Eac3SurroundMode::*|null
     */
    public function getSurroundMode(): ?string
    {
        return $this->surroundMode;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->attenuationControl) {
            if (!Eac3AttenuationControl::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "attenuationControl" for "%s". The value "%s" is not a valid "Eac3AttenuationControl".', __CLASS__, $v));
            }
            $payload['attenuationControl'] = $v;
        }
        if (null !== $v = $this->bitrate) {
            $payload['bitrate'] = $v;
        }
        if (null !== $v = $this->bitstreamMode) {
            if (!Eac3BitstreamMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "bitstreamMode" for "%s". The value "%s" is not a valid "Eac3BitstreamMode".', __CLASS__, $v));
            }
            $payload['bitstreamMode'] = $v;
        }
        if (null !== $v = $this->codingMode) {
            if (!Eac3CodingMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "codingMode" for "%s". The value "%s" is not a valid "Eac3CodingMode".', __CLASS__, $v));
            }
            $payload['codingMode'] = $v;
        }
        if (null !== $v = $this->dcFilter) {
            if (!Eac3DcFilter::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "dcFilter" for "%s". The value "%s" is not a valid "Eac3DcFilter".', __CLASS__, $v));
            }
            $payload['dcFilter'] = $v;
        }
        if (null !== $v = $this->dialnorm) {
            $payload['dialnorm'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionLine) {
            if (!Eac3DynamicRangeCompressionLine::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicRangeCompressionLine" for "%s". The value "%s" is not a valid "Eac3DynamicRangeCompressionLine".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionLine'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionRf) {
            if (!Eac3DynamicRangeCompressionRf::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicRangeCompressionRf" for "%s". The value "%s" is not a valid "Eac3DynamicRangeCompressionRf".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionRf'] = $v;
        }
        if (null !== $v = $this->lfeControl) {
            if (!Eac3LfeControl::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "lfeControl" for "%s". The value "%s" is not a valid "Eac3LfeControl".', __CLASS__, $v));
            }
            $payload['lfeControl'] = $v;
        }
        if (null !== $v = $this->lfeFilter) {
            if (!Eac3LfeFilter::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "lfeFilter" for "%s". The value "%s" is not a valid "Eac3LfeFilter".', __CLASS__, $v));
            }
            $payload['lfeFilter'] = $v;
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
        if (null !== $v = $this->metadataControl) {
            if (!Eac3MetadataControl::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "metadataControl" for "%s". The value "%s" is not a valid "Eac3MetadataControl".', __CLASS__, $v));
            }
            $payload['metadataControl'] = $v;
        }
        if (null !== $v = $this->passthroughControl) {
            if (!Eac3PassthroughControl::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "passthroughControl" for "%s". The value "%s" is not a valid "Eac3PassthroughControl".', __CLASS__, $v));
            }
            $payload['passthroughControl'] = $v;
        }
        if (null !== $v = $this->phaseControl) {
            if (!Eac3PhaseControl::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "phaseControl" for "%s". The value "%s" is not a valid "Eac3PhaseControl".', __CLASS__, $v));
            }
            $payload['phaseControl'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }
        if (null !== $v = $this->stereoDownmix) {
            if (!Eac3StereoDownmix::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "stereoDownmix" for "%s". The value "%s" is not a valid "Eac3StereoDownmix".', __CLASS__, $v));
            }
            $payload['stereoDownmix'] = $v;
        }
        if (null !== $v = $this->surroundExMode) {
            if (!Eac3SurroundExMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "surroundExMode" for "%s". The value "%s" is not a valid "Eac3SurroundExMode".', __CLASS__, $v));
            }
            $payload['surroundExMode'] = $v;
        }
        if (null !== $v = $this->surroundMode) {
            if (!Eac3SurroundMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "surroundMode" for "%s". The value "%s" is not a valid "Eac3SurroundMode".', __CLASS__, $v));
            }
            $payload['surroundMode'] = $v;
        }

        return $payload;
    }
}
