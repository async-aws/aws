<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Eac3AtmosBitstreamMode;
use AsyncAws\MediaConvert\Enum\Eac3AtmosCodingMode;
use AsyncAws\MediaConvert\Enum\Eac3AtmosDialogueIntelligence;
use AsyncAws\MediaConvert\Enum\Eac3AtmosDownmixControl;
use AsyncAws\MediaConvert\Enum\Eac3AtmosDynamicRangeCompressionLine;
use AsyncAws\MediaConvert\Enum\Eac3AtmosDynamicRangeCompressionRf;
use AsyncAws\MediaConvert\Enum\Eac3AtmosDynamicRangeControl;
use AsyncAws\MediaConvert\Enum\Eac3AtmosMeteringMode;
use AsyncAws\MediaConvert\Enum\Eac3AtmosStereoDownmix;
use AsyncAws\MediaConvert\Enum\Eac3AtmosSurroundExMode;

/**
 * Required when you set (Codec) under (AudioDescriptions)>(CodecSettings) to the value EAC3_ATMOS.
 */
final class Eac3AtmosSettings
{
    /**
     * Specify the average bitrate for this output in bits per second. Valid values: 384k, 448k, 576k, 640k, 768k, 1024k
     * Default value: 448k Note that MediaConvert supports 384k only with channel-based immersive (CBI) 7.1.4 and 5.1.4
     * inputs. For CBI 9.1.6 and other input types, MediaConvert automatically increases your output bitrate to 448k.
     */
    private $bitrate;

    /**
     * Specify the bitstream mode for the E-AC-3 stream that the encoder emits. For more information about the EAC3
     * bitstream mode, see ATSC A/52-2012 (Annex E).
     */
    private $bitstreamMode;

    /**
     * The coding mode for Dolby Digital Plus JOC (Atmos).
     */
    private $codingMode;

    /**
     * Enable Dolby Dialogue Intelligence to adjust loudness based on dialogue analysis.
     */
    private $dialogueIntelligence;

    /**
     * Specify whether MediaConvert should use any downmix metadata from your input file. Keep the default value, Custom
     * (SPECIFIED) to provide downmix values in your job settings. Choose Follow source (INITIALIZE_FROM_SOURCE) to use the
     * metadata from your input. Related settings--Use these settings to specify your downmix values: Left only/Right only
     * surround (LoRoSurroundMixLevel), Left total/Right total surround (LtRtSurroundMixLevel), Left total/Right total
     * center (LtRtCenterMixLevel), Left only/Right only center (LoRoCenterMixLevel), and Stereo downmix (StereoDownmix).
     * When you keep Custom (SPECIFIED) for Downmix control (DownmixControl) and you don't specify values for the related
     * settings, MediaConvert uses default values for those settings.
     */
    private $downmixControl;

    /**
     * Choose the Dolby dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the Dolby
     * stream for the line operating mode. Default value: Film light (ATMOS_STORAGE_DDP_COMPR_FILM_LIGHT) Related setting:
     * To have MediaConvert use the value you specify here, keep the default value, Custom (SPECIFIED) for the setting
     * Dynamic range control (DynamicRangeControl). Otherwise, MediaConvert ignores Dynamic range compression line
     * (DynamicRangeCompressionLine). For information about the Dolby DRC operating modes and profiles, see the Dynamic
     * Range Control chapter of the Dolby Metadata Guide at
     * https://developer.dolby.com/globalassets/professional/documents/dolby-metadata-guide.pdf.
     */
    private $dynamicRangeCompressionLine;

    /**
     * Choose the Dolby dynamic range control (DRC) profile that MediaConvert uses when encoding the metadata in the Dolby
     * stream for the RF operating mode. Default value: Film light (ATMOS_STORAGE_DDP_COMPR_FILM_LIGHT) Related setting: To
     * have MediaConvert use the value you specify here, keep the default value, Custom (SPECIFIED) for the setting Dynamic
     * range control (DynamicRangeControl). Otherwise, MediaConvert ignores Dynamic range compression RF
     * (DynamicRangeCompressionRf). For information about the Dolby DRC operating modes and profiles, see the Dynamic Range
     * Control chapter of the Dolby Metadata Guide at
     * https://developer.dolby.com/globalassets/professional/documents/dolby-metadata-guide.pdf.
     */
    private $dynamicRangeCompressionRf;

    /**
     * Specify whether MediaConvert should use any dynamic range control metadata from your input file. Keep the default
     * value, Custom (SPECIFIED), to provide dynamic range control values in your job settings. Choose Follow source
     * (INITIALIZE_FROM_SOURCE) to use the metadata from your input. Related settings--Use these settings to specify your
     * dynamic range control values: Dynamic range compression line (DynamicRangeCompressionLine) and Dynamic range
     * compression RF (DynamicRangeCompressionRf). When you keep the value Custom (SPECIFIED) for Dynamic range control
     * (DynamicRangeControl) and you don't specify values for the related settings, MediaConvert uses default values for
     * those settings.
     */
    private $dynamicRangeControl;

    /**
     * Specify a value for the following Dolby Atmos setting: Left only/Right only center mix (Lo/Ro center). MediaConvert
     * uses this value for downmixing. Default value: -3 dB (ATMOS_STORAGE_DDP_MIXLEV_MINUS_3_DB). Valid values: 3.0, 1.5,
     * 0.0, -1.5, -3.0, -4.5, and -6.0. Related setting: How the service uses this value depends on the value that you
     * choose for Stereo downmix (Eac3AtmosStereoDownmix). Related setting: To have MediaConvert use this value, keep the
     * default value, Custom (SPECIFIED) for the setting Downmix control (DownmixControl). Otherwise, MediaConvert ignores
     * Left only/Right only center (LoRoCenterMixLevel).
     */
    private $loRoCenterMixLevel;

    /**
     * Specify a value for the following Dolby Atmos setting: Left only/Right only (Lo/Ro surround). MediaConvert uses this
     * value for downmixing. Default value: -3 dB (ATMOS_STORAGE_DDP_MIXLEV_MINUS_3_DB). Valid values: -1.5, -3.0, -4.5,
     * -6.0, and -60. The value -60 mutes the channel. Related setting: How the service uses this value depends on the value
     * that you choose for Stereo downmix (Eac3AtmosStereoDownmix). Related setting: To have MediaConvert use this value,
     * keep the default value, Custom (SPECIFIED) for the setting Downmix control (DownmixControl). Otherwise, MediaConvert
     * ignores Left only/Right only surround (LoRoSurroundMixLevel).
     */
    private $loRoSurroundMixLevel;

    /**
     * Specify a value for the following Dolby Atmos setting: Left total/Right total center mix (Lt/Rt center). MediaConvert
     * uses this value for downmixing. Default value: -3 dB (ATMOS_STORAGE_DDP_MIXLEV_MINUS_3_DB) Valid values: 3.0, 1.5,
     * 0.0, -1.5, -3.0, -4.5, and -6.0. Related setting: How the service uses this value depends on the value that you
     * choose for Stereo downmix (Eac3AtmosStereoDownmix). Related setting: To have MediaConvert use this value, keep the
     * default value, Custom (SPECIFIED) for the setting Downmix control (DownmixControl). Otherwise, MediaConvert ignores
     * Left total/Right total center (LtRtCenterMixLevel).
     */
    private $ltRtCenterMixLevel;

    /**
     * Specify a value for the following Dolby Atmos setting: Left total/Right total surround mix (Lt/Rt surround).
     * MediaConvert uses this value for downmixing. Default value: -3 dB (ATMOS_STORAGE_DDP_MIXLEV_MINUS_3_DB) Valid values:
     * -1.5, -3.0, -4.5, -6.0, and -60. The value -60 mutes the channel. Related setting: How the service uses this value
     * depends on the value that you choose for Stereo downmix (Eac3AtmosStereoDownmix). Related setting: To have
     * MediaConvert use this value, keep the default value, Custom (SPECIFIED) for the setting Downmix control
     * (DownmixControl). Otherwise, the service ignores Left total/Right total surround (LtRtSurroundMixLevel).
     */
    private $ltRtSurroundMixLevel;

    /**
     * Choose how the service meters the loudness of your audio.
     */
    private $meteringMode;

    /**
     * This value is always 48000. It represents the sample rate in Hz.
     */
    private $sampleRate;

    /**
     * Specify the percentage of audio content, from 0% to 100%, that must be speech in order for the encoder to use the
     * measured speech loudness as the overall program loudness. Default value: 15%.
     */
    private $speechThreshold;

    /**
     * Choose how the service does stereo downmixing. Default value: Not indicated (ATMOS_STORAGE_DDP_DMIXMOD_NOT_INDICATED)
     * Related setting: To have MediaConvert use this value, keep the default value, Custom (SPECIFIED) for the setting
     * Downmix control (DownmixControl). Otherwise, MediaConvert ignores Stereo downmix (StereoDownmix).
     */
    private $stereoDownmix;

    /**
     * Specify whether your input audio has an additional center rear surround channel matrix encoded into your left and
     * right surround channels.
     */
    private $surroundExMode;

    /**
     * @param array{
     *   Bitrate?: null|int,
     *   BitstreamMode?: null|Eac3AtmosBitstreamMode::*,
     *   CodingMode?: null|Eac3AtmosCodingMode::*,
     *   DialogueIntelligence?: null|Eac3AtmosDialogueIntelligence::*,
     *   DownmixControl?: null|Eac3AtmosDownmixControl::*,
     *   DynamicRangeCompressionLine?: null|Eac3AtmosDynamicRangeCompressionLine::*,
     *   DynamicRangeCompressionRf?: null|Eac3AtmosDynamicRangeCompressionRf::*,
     *   DynamicRangeControl?: null|Eac3AtmosDynamicRangeControl::*,
     *   LoRoCenterMixLevel?: null|float,
     *   LoRoSurroundMixLevel?: null|float,
     *   LtRtCenterMixLevel?: null|float,
     *   LtRtSurroundMixLevel?: null|float,
     *   MeteringMode?: null|Eac3AtmosMeteringMode::*,
     *   SampleRate?: null|int,
     *   SpeechThreshold?: null|int,
     *   StereoDownmix?: null|Eac3AtmosStereoDownmix::*,
     *   SurroundExMode?: null|Eac3AtmosSurroundExMode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->bitstreamMode = $input['BitstreamMode'] ?? null;
        $this->codingMode = $input['CodingMode'] ?? null;
        $this->dialogueIntelligence = $input['DialogueIntelligence'] ?? null;
        $this->downmixControl = $input['DownmixControl'] ?? null;
        $this->dynamicRangeCompressionLine = $input['DynamicRangeCompressionLine'] ?? null;
        $this->dynamicRangeCompressionRf = $input['DynamicRangeCompressionRf'] ?? null;
        $this->dynamicRangeControl = $input['DynamicRangeControl'] ?? null;
        $this->loRoCenterMixLevel = $input['LoRoCenterMixLevel'] ?? null;
        $this->loRoSurroundMixLevel = $input['LoRoSurroundMixLevel'] ?? null;
        $this->ltRtCenterMixLevel = $input['LtRtCenterMixLevel'] ?? null;
        $this->ltRtSurroundMixLevel = $input['LtRtSurroundMixLevel'] ?? null;
        $this->meteringMode = $input['MeteringMode'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
        $this->speechThreshold = $input['SpeechThreshold'] ?? null;
        $this->stereoDownmix = $input['StereoDownmix'] ?? null;
        $this->surroundExMode = $input['SurroundExMode'] ?? null;
    }

    /**
     * @param array{
     *   Bitrate?: null|int,
     *   BitstreamMode?: null|Eac3AtmosBitstreamMode::*,
     *   CodingMode?: null|Eac3AtmosCodingMode::*,
     *   DialogueIntelligence?: null|Eac3AtmosDialogueIntelligence::*,
     *   DownmixControl?: null|Eac3AtmosDownmixControl::*,
     *   DynamicRangeCompressionLine?: null|Eac3AtmosDynamicRangeCompressionLine::*,
     *   DynamicRangeCompressionRf?: null|Eac3AtmosDynamicRangeCompressionRf::*,
     *   DynamicRangeControl?: null|Eac3AtmosDynamicRangeControl::*,
     *   LoRoCenterMixLevel?: null|float,
     *   LoRoSurroundMixLevel?: null|float,
     *   LtRtCenterMixLevel?: null|float,
     *   LtRtSurroundMixLevel?: null|float,
     *   MeteringMode?: null|Eac3AtmosMeteringMode::*,
     *   SampleRate?: null|int,
     *   SpeechThreshold?: null|int,
     *   StereoDownmix?: null|Eac3AtmosStereoDownmix::*,
     *   SurroundExMode?: null|Eac3AtmosSurroundExMode::*,
     * }|Eac3AtmosSettings $input
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
     * @return Eac3AtmosBitstreamMode::*|null
     */
    public function getBitstreamMode(): ?string
    {
        return $this->bitstreamMode;
    }

    /**
     * @return Eac3AtmosCodingMode::*|null
     */
    public function getCodingMode(): ?string
    {
        return $this->codingMode;
    }

    /**
     * @return Eac3AtmosDialogueIntelligence::*|null
     */
    public function getDialogueIntelligence(): ?string
    {
        return $this->dialogueIntelligence;
    }

    /**
     * @return Eac3AtmosDownmixControl::*|null
     */
    public function getDownmixControl(): ?string
    {
        return $this->downmixControl;
    }

    /**
     * @return Eac3AtmosDynamicRangeCompressionLine::*|null
     */
    public function getDynamicRangeCompressionLine(): ?string
    {
        return $this->dynamicRangeCompressionLine;
    }

    /**
     * @return Eac3AtmosDynamicRangeCompressionRf::*|null
     */
    public function getDynamicRangeCompressionRf(): ?string
    {
        return $this->dynamicRangeCompressionRf;
    }

    /**
     * @return Eac3AtmosDynamicRangeControl::*|null
     */
    public function getDynamicRangeControl(): ?string
    {
        return $this->dynamicRangeControl;
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
     * @return Eac3AtmosMeteringMode::*|null
     */
    public function getMeteringMode(): ?string
    {
        return $this->meteringMode;
    }

    public function getSampleRate(): ?int
    {
        return $this->sampleRate;
    }

    public function getSpeechThreshold(): ?int
    {
        return $this->speechThreshold;
    }

    /**
     * @return Eac3AtmosStereoDownmix::*|null
     */
    public function getStereoDownmix(): ?string
    {
        return $this->stereoDownmix;
    }

    /**
     * @return Eac3AtmosSurroundExMode::*|null
     */
    public function getSurroundExMode(): ?string
    {
        return $this->surroundExMode;
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
            if (!Eac3AtmosBitstreamMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "bitstreamMode" for "%s". The value "%s" is not a valid "Eac3AtmosBitstreamMode".', __CLASS__, $v));
            }
            $payload['bitstreamMode'] = $v;
        }
        if (null !== $v = $this->codingMode) {
            if (!Eac3AtmosCodingMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "codingMode" for "%s". The value "%s" is not a valid "Eac3AtmosCodingMode".', __CLASS__, $v));
            }
            $payload['codingMode'] = $v;
        }
        if (null !== $v = $this->dialogueIntelligence) {
            if (!Eac3AtmosDialogueIntelligence::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "dialogueIntelligence" for "%s". The value "%s" is not a valid "Eac3AtmosDialogueIntelligence".', __CLASS__, $v));
            }
            $payload['dialogueIntelligence'] = $v;
        }
        if (null !== $v = $this->downmixControl) {
            if (!Eac3AtmosDownmixControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "downmixControl" for "%s". The value "%s" is not a valid "Eac3AtmosDownmixControl".', __CLASS__, $v));
            }
            $payload['downmixControl'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionLine) {
            if (!Eac3AtmosDynamicRangeCompressionLine::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "dynamicRangeCompressionLine" for "%s". The value "%s" is not a valid "Eac3AtmosDynamicRangeCompressionLine".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionLine'] = $v;
        }
        if (null !== $v = $this->dynamicRangeCompressionRf) {
            if (!Eac3AtmosDynamicRangeCompressionRf::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "dynamicRangeCompressionRf" for "%s". The value "%s" is not a valid "Eac3AtmosDynamicRangeCompressionRf".', __CLASS__, $v));
            }
            $payload['dynamicRangeCompressionRf'] = $v;
        }
        if (null !== $v = $this->dynamicRangeControl) {
            if (!Eac3AtmosDynamicRangeControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "dynamicRangeControl" for "%s". The value "%s" is not a valid "Eac3AtmosDynamicRangeControl".', __CLASS__, $v));
            }
            $payload['dynamicRangeControl'] = $v;
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
        if (null !== $v = $this->meteringMode) {
            if (!Eac3AtmosMeteringMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "meteringMode" for "%s". The value "%s" is not a valid "Eac3AtmosMeteringMode".', __CLASS__, $v));
            }
            $payload['meteringMode'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }
        if (null !== $v = $this->speechThreshold) {
            $payload['speechThreshold'] = $v;
        }
        if (null !== $v = $this->stereoDownmix) {
            if (!Eac3AtmosStereoDownmix::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "stereoDownmix" for "%s". The value "%s" is not a valid "Eac3AtmosStereoDownmix".', __CLASS__, $v));
            }
            $payload['stereoDownmix'] = $v;
        }
        if (null !== $v = $this->surroundExMode) {
            if (!Eac3AtmosSurroundExMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "surroundExMode" for "%s". The value "%s" is not a valid "Eac3AtmosSurroundExMode".', __CLASS__, $v));
            }
            $payload['surroundExMode'] = $v;
        }

        return $payload;
    }
}
