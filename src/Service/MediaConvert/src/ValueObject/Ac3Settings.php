<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Ac3BitstreamMode;
use AsyncAws\MediaConvert\Enum\Ac3CodingMode;
use AsyncAws\MediaConvert\Enum\Ac3DynamicRangeCompressionLine;
use AsyncAws\MediaConvert\Enum\Ac3DynamicRangeCompressionProfile;
use AsyncAws\MediaConvert\Enum\Ac3DynamicRangeCompressionRf;
use AsyncAws\MediaConvert\Enum\Ac3LfeFilter;
use AsyncAws\MediaConvert\Enum\Ac3MetadataControl;

/**
 * Required when you set Codec to the value AC3.
 */
final class Ac3Settings
{
    /**
     * Specify the average bitrate in bits per second. The bitrate that you specify must be a multiple of 8000 within the
     * allowed minimum and maximum values. Leave blank to use the default bitrate for the coding mode you select according
     * ETSI TS 102 366. Valid bitrates for coding mode 1/0: Default: 96000. Minimum: 64000. Maximum: 128000. Valid bitrates
     * for coding mode 1/1: Default: 192000. Minimum: 128000. Maximum: 384000. Valid bitrates for coding mode 2/0: Default:
     * 192000. Minimum: 128000. Maximum: 384000. Valid bitrates for coding mode 3/2 with FLE: Default: 384000. Minimum:
     * 384000. Maximum: 640000.
     *
     * @var int|null
     */
    private $bitrate;

    /**
     * Specify the bitstream mode for the AC-3 stream that the encoder emits. For more information about the AC3 bitstream
     * mode, see ATSC A/52-2012 (Annex E).
     *
     * @var Ac3BitstreamMode::*|null
     */
    private $bitstreamMode;

    /**
     * Dolby Digital coding mode. Determines number of channels.
     *
     * @var Ac3CodingMode::*|null
     */
    private $codingMode;

    /**
     * Sets the dialnorm for the output. If blank and input audio is Dolby Digital, dialnorm will be passed through.
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
     * @var Ac3DynamicRangeCompressionLine::*|null
     */
    private $dynamicRangeCompressionLine;

    /**
     * When you want to add Dolby dynamic range compression (DRC) signaling to your output stream, we recommend that you use
     * the mode-specific settings instead of Dynamic range compression profile. The mode-specific settings are Dynamic range
     * compression profile, line mode and Dynamic range compression profile, RF mode. Note that when you specify values for
     * all three settings, MediaConvert ignores the value of this setting in favor of the mode-specific settings. If you do
     * use this setting instead of the mode-specific settings, choose None to leave out DRC signaling. Keep the default Film
     * standard to set the profile to Dolby's film standard profile for all operating modes.
     *
     * @var Ac3DynamicRangeCompressionProfile::*|null
     */
    private $dynamicRangeCompressionProfile;

    /**
     * Choose the Dolby Digital dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the
     * Dolby Digital stream for the RF operating mode. Related setting: When you use this setting, MediaConvert ignores any
     * value you provide for Dynamic range compression profile. For information about the Dolby Digital DRC operating modes
     * and profiles, see the Dynamic Range Control chapter of the Dolby Metadata Guide at
     * https://developer.dolby.com/globalassets/professional/documents/dolby-metadata-guide.pdf.
     *
     * @var Ac3DynamicRangeCompressionRf::*|null
     */
    private $dynamicRangeCompressionRf;

    /**
     * Applies a 120Hz lowpass filter to the LFE channel prior to encoding. Only valid with 3_2_LFE coding mode.
     *
     * @var Ac3LfeFilter::*|null
     */
    private $lfeFilter;

    /**
     * When set to FOLLOW_INPUT, encoder metadata will be sourced from the DD, DD+, or DolbyE decoder that supplied this
     * audio data. If audio was not supplied from one of these streams, then the static metadata settings will be used.
     *
     * @var Ac3MetadataControl::*|null
     */
    private $metadataControl;

    /**
     * This value is always 48000. It represents the sample rate in Hz.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * @param array{
     *   Bitrate?: int|null,
     *   BitstreamMode?: Ac3BitstreamMode::*|null,
     *   CodingMode?: Ac3CodingMode::*|null,
     *   Dialnorm?: int|null,
     *   DynamicRangeCompressionLine?: Ac3DynamicRangeCompressionLine::*|null,
     *   DynamicRangeCompressionProfile?: Ac3DynamicRangeCompressionProfile::*|null,
     *   DynamicRangeCompressionRf?: Ac3DynamicRangeCompressionRf::*|null,
     *   LfeFilter?: Ac3LfeFilter::*|null,
     *   MetadataControl?: Ac3MetadataControl::*|null,
     *   SampleRate?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->bitstreamMode = $input['BitstreamMode'] ?? null;
        $this->codingMode = $input['CodingMode'] ?? null;
        $this->dialnorm = $input['Dialnorm'] ?? null;
        $this->dynamicRangeCompressionLine = $input['DynamicRangeCompressionLine'] ?? null;
        $this->dynamicRangeCompressionProfile = $input['DynamicRangeCompressionProfile'] ?? null;
        $this->dynamicRangeCompressionRf = $input['DynamicRangeCompressionRf'] ?? null;
        $this->lfeFilter = $input['LfeFilter'] ?? null;
        $this->metadataControl = $input['MetadataControl'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
    }

    /**
     * @param array{
     *   Bitrate?: int|null,
     *   BitstreamMode?: Ac3BitstreamMode::*|null,
     *   CodingMode?: Ac3CodingMode::*|null,
     *   Dialnorm?: int|null,
     *   DynamicRangeCompressionLine?: Ac3DynamicRangeCompressionLine::*|null,
     *   DynamicRangeCompressionProfile?: Ac3DynamicRangeCompressionProfile::*|null,
     *   DynamicRangeCompressionRf?: Ac3DynamicRangeCompressionRf::*|null,
     *   LfeFilter?: Ac3LfeFilter::*|null,
     *   MetadataControl?: Ac3MetadataControl::*|null,
     *   SampleRate?: int|null,
     * }|Ac3Settings $input
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
     * @return Ac3BitstreamMode::*|null
     */
    public function getBitstreamMode(): ?string
    {
        return $this->bitstreamMode;
    }

    /**
     * @return Ac3CodingMode::*|null
     */
    public function getCodingMode(): ?string
    {
        return $this->codingMode;
    }

    public function getDialnorm(): ?int
    {
        return $this->dialnorm;
    }

    /**
     * @return Ac3DynamicRangeCompressionLine::*|null
     */
    public function getDynamicRangeCompressionLine(): ?string
    {
        return $this->dynamicRangeCompressionLine;
    }

    /**
     * @return Ac3DynamicRangeCompressionProfile::*|null
     */
    public function getDynamicRangeCompressionProfile(): ?string
    {
        return $this->dynamicRangeCompressionProfile;
    }

    /**
     * @return Ac3DynamicRangeCompressionRf::*|null
     */
    public function getDynamicRangeCompressionRf(): ?string
    {
        return $this->dynamicRangeCompressionRf;
    }

    /**
     * @return Ac3LfeFilter::*|null
     */
    public function getLfeFilter(): ?string
    {
        return $this->lfeFilter;
    }

    /**
     * @return Ac3MetadataControl::*|null
     */
    public function getMetadataControl(): ?string
    {
        return $this->metadataControl;
    }

    public function getSampleRate(): ?int
    {
        return $this->sampleRate;
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
            if (!Ac3BitstreamMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "bitstreamMode" for "%s". The value "%s" is not a valid "Ac3BitstreamMode".', __CLASS__, $v));
            }
            $payload['bitstreamMode'] = $v;
        }
        if (null !== $v = $this->codingMode) {
            if (!Ac3CodingMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "codingMode" for "%s". The value "%s" is not a valid "Ac3CodingMode".', __CLASS__, $v));
            }
            $payload['codingMode'] = $v;
        }
        if (null !== $v = $this->dialnorm) {
            $payload['dialnorm'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionLine) {
            if (!Ac3DynamicRangeCompressionLine::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicRangeCompressionLine" for "%s". The value "%s" is not a valid "Ac3DynamicRangeCompressionLine".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionLine'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionProfile) {
            if (!Ac3DynamicRangeCompressionProfile::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicRangeCompressionProfile" for "%s". The value "%s" is not a valid "Ac3DynamicRangeCompressionProfile".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionProfile'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionRf) {
            if (!Ac3DynamicRangeCompressionRf::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "dynamicRangeCompressionRf" for "%s". The value "%s" is not a valid "Ac3DynamicRangeCompressionRf".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionRf'] = $v;
        }
        if (null !== $v = $this->lfeFilter) {
            if (!Ac3LfeFilter::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "lfeFilter" for "%s". The value "%s" is not a valid "Ac3LfeFilter".', __CLASS__, $v));
            }
            $payload['lfeFilter'] = $v;
        }
        if (null !== $v = $this->metadataControl) {
            if (!Ac3MetadataControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "metadataControl" for "%s". The value "%s" is not a valid "Ac3MetadataControl".', __CLASS__, $v));
            }
            $payload['metadataControl'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }

        return $payload;
    }
}
