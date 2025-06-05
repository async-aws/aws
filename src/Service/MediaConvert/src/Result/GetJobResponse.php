<?php

namespace AsyncAws\MediaConvert\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\MediaConvert\Enum\AudioChannelTag;
use AsyncAws\MediaConvert\Enum\FrameMetricType;
use AsyncAws\MediaConvert\Enum\HlsAdMarkers;
use AsyncAws\MediaConvert\Enum\TeletextPageType;
use AsyncAws\MediaConvert\ValueObject\AacSettings;
use AsyncAws\MediaConvert\ValueObject\Ac3Settings;
use AsyncAws\MediaConvert\ValueObject\AccelerationSettings;
use AsyncAws\MediaConvert\ValueObject\AdvancedInputFilterSettings;
use AsyncAws\MediaConvert\ValueObject\AiffSettings;
use AsyncAws\MediaConvert\ValueObject\AllowedRenditionSize;
use AsyncAws\MediaConvert\ValueObject\AncillarySourceSettings;
use AsyncAws\MediaConvert\ValueObject\AudioChannelTaggingSettings;
use AsyncAws\MediaConvert\ValueObject\AudioCodecSettings;
use AsyncAws\MediaConvert\ValueObject\AudioDescription;
use AsyncAws\MediaConvert\ValueObject\AudioNormalizationSettings;
use AsyncAws\MediaConvert\ValueObject\AudioSelector;
use AsyncAws\MediaConvert\ValueObject\AudioSelectorGroup;
use AsyncAws\MediaConvert\ValueObject\AutomatedAbrRule;
use AsyncAws\MediaConvert\ValueObject\AutomatedAbrSettings;
use AsyncAws\MediaConvert\ValueObject\AutomatedEncodingSettings;
use AsyncAws\MediaConvert\ValueObject\Av1QvbrSettings;
use AsyncAws\MediaConvert\ValueObject\Av1Settings;
use AsyncAws\MediaConvert\ValueObject\AvailBlanking;
use AsyncAws\MediaConvert\ValueObject\AvcIntraSettings;
use AsyncAws\MediaConvert\ValueObject\AvcIntraUhdSettings;
use AsyncAws\MediaConvert\ValueObject\BandwidthReductionFilter;
use AsyncAws\MediaConvert\ValueObject\BurninDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\CaptionDescription;
use AsyncAws\MediaConvert\ValueObject\CaptionDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\CaptionSelector;
use AsyncAws\MediaConvert\ValueObject\CaptionSourceFramerate;
use AsyncAws\MediaConvert\ValueObject\CaptionSourceSettings;
use AsyncAws\MediaConvert\ValueObject\ChannelMapping;
use AsyncAws\MediaConvert\ValueObject\ClipLimits;
use AsyncAws\MediaConvert\ValueObject\CmafAdditionalManifest;
use AsyncAws\MediaConvert\ValueObject\CmafEncryptionSettings;
use AsyncAws\MediaConvert\ValueObject\CmafGroupSettings;
use AsyncAws\MediaConvert\ValueObject\CmafImageBasedTrickPlaySettings;
use AsyncAws\MediaConvert\ValueObject\CmfcSettings;
use AsyncAws\MediaConvert\ValueObject\ColorConversion3DLUTSetting;
use AsyncAws\MediaConvert\ValueObject\ColorCorrector;
use AsyncAws\MediaConvert\ValueObject\ContainerSettings;
use AsyncAws\MediaConvert\ValueObject\DashAdditionalManifest;
use AsyncAws\MediaConvert\ValueObject\DashIsoEncryptionSettings;
use AsyncAws\MediaConvert\ValueObject\DashIsoGroupSettings;
use AsyncAws\MediaConvert\ValueObject\DashIsoImageBasedTrickPlaySettings;
use AsyncAws\MediaConvert\ValueObject\Deinterlacer;
use AsyncAws\MediaConvert\ValueObject\DestinationSettings;
use AsyncAws\MediaConvert\ValueObject\DolbyVision;
use AsyncAws\MediaConvert\ValueObject\DolbyVisionLevel6Metadata;
use AsyncAws\MediaConvert\ValueObject\DvbNitSettings;
use AsyncAws\MediaConvert\ValueObject\DvbSdtSettings;
use AsyncAws\MediaConvert\ValueObject\DvbSubDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\DvbSubSourceSettings;
use AsyncAws\MediaConvert\ValueObject\DvbTdtSettings;
use AsyncAws\MediaConvert\ValueObject\DynamicAudioSelector;
use AsyncAws\MediaConvert\ValueObject\Eac3AtmosSettings;
use AsyncAws\MediaConvert\ValueObject\Eac3Settings;
use AsyncAws\MediaConvert\ValueObject\EmbeddedDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\EmbeddedSourceSettings;
use AsyncAws\MediaConvert\ValueObject\EncryptionContractConfiguration;
use AsyncAws\MediaConvert\ValueObject\EsamManifestConfirmConditionNotification;
use AsyncAws\MediaConvert\ValueObject\EsamSettings;
use AsyncAws\MediaConvert\ValueObject\EsamSignalProcessingNotification;
use AsyncAws\MediaConvert\ValueObject\ExtendedDataServices;
use AsyncAws\MediaConvert\ValueObject\F4vSettings;
use AsyncAws\MediaConvert\ValueObject\FileGroupSettings;
use AsyncAws\MediaConvert\ValueObject\FileSourceSettings;
use AsyncAws\MediaConvert\ValueObject\FlacSettings;
use AsyncAws\MediaConvert\ValueObject\ForceIncludeRenditionSize;
use AsyncAws\MediaConvert\ValueObject\FrameCaptureSettings;
use AsyncAws\MediaConvert\ValueObject\GifSettings;
use AsyncAws\MediaConvert\ValueObject\H264QvbrSettings;
use AsyncAws\MediaConvert\ValueObject\H264Settings;
use AsyncAws\MediaConvert\ValueObject\H265QvbrSettings;
use AsyncAws\MediaConvert\ValueObject\H265Settings;
use AsyncAws\MediaConvert\ValueObject\Hdr10Metadata;
use AsyncAws\MediaConvert\ValueObject\Hdr10Plus;
use AsyncAws\MediaConvert\ValueObject\HlsAdditionalManifest;
use AsyncAws\MediaConvert\ValueObject\HlsCaptionLanguageMapping;
use AsyncAws\MediaConvert\ValueObject\HlsEncryptionSettings;
use AsyncAws\MediaConvert\ValueObject\HlsGroupSettings;
use AsyncAws\MediaConvert\ValueObject\HlsImageBasedTrickPlaySettings;
use AsyncAws\MediaConvert\ValueObject\HlsRenditionGroupSettings;
use AsyncAws\MediaConvert\ValueObject\HlsSettings;
use AsyncAws\MediaConvert\ValueObject\HopDestination;
use AsyncAws\MediaConvert\ValueObject\Id3Insertion;
use AsyncAws\MediaConvert\ValueObject\ImageInserter;
use AsyncAws\MediaConvert\ValueObject\ImscDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\Input;
use AsyncAws\MediaConvert\ValueObject\InputClipping;
use AsyncAws\MediaConvert\ValueObject\InputDecryptionSettings;
use AsyncAws\MediaConvert\ValueObject\InputVideoGenerator;
use AsyncAws\MediaConvert\ValueObject\InsertableImage;
use AsyncAws\MediaConvert\ValueObject\Job;
use AsyncAws\MediaConvert\ValueObject\JobMessages;
use AsyncAws\MediaConvert\ValueObject\JobSettings;
use AsyncAws\MediaConvert\ValueObject\KantarWatermarkSettings;
use AsyncAws\MediaConvert\ValueObject\M2tsScte35Esam;
use AsyncAws\MediaConvert\ValueObject\M2tsSettings;
use AsyncAws\MediaConvert\ValueObject\M3u8Settings;
use AsyncAws\MediaConvert\ValueObject\MinBottomRenditionSize;
use AsyncAws\MediaConvert\ValueObject\MinTopRenditionSize;
use AsyncAws\MediaConvert\ValueObject\MotionImageInserter;
use AsyncAws\MediaConvert\ValueObject\MotionImageInsertionFramerate;
use AsyncAws\MediaConvert\ValueObject\MotionImageInsertionOffset;
use AsyncAws\MediaConvert\ValueObject\MovSettings;
use AsyncAws\MediaConvert\ValueObject\Mp2Settings;
use AsyncAws\MediaConvert\ValueObject\Mp3Settings;
use AsyncAws\MediaConvert\ValueObject\Mp4Settings;
use AsyncAws\MediaConvert\ValueObject\MpdSettings;
use AsyncAws\MediaConvert\ValueObject\Mpeg2Settings;
use AsyncAws\MediaConvert\ValueObject\MsSmoothAdditionalManifest;
use AsyncAws\MediaConvert\ValueObject\MsSmoothEncryptionSettings;
use AsyncAws\MediaConvert\ValueObject\MsSmoothGroupSettings;
use AsyncAws\MediaConvert\ValueObject\MxfSettings;
use AsyncAws\MediaConvert\ValueObject\MxfXavcProfileSettings;
use AsyncAws\MediaConvert\ValueObject\NexGuardFileMarkerSettings;
use AsyncAws\MediaConvert\ValueObject\NielsenConfiguration;
use AsyncAws\MediaConvert\ValueObject\NielsenNonLinearWatermarkSettings;
use AsyncAws\MediaConvert\ValueObject\NoiseReducer;
use AsyncAws\MediaConvert\ValueObject\NoiseReducerFilterSettings;
use AsyncAws\MediaConvert\ValueObject\NoiseReducerSpatialFilterSettings;
use AsyncAws\MediaConvert\ValueObject\NoiseReducerTemporalFilterSettings;
use AsyncAws\MediaConvert\ValueObject\OpusSettings;
use AsyncAws\MediaConvert\ValueObject\Output;
use AsyncAws\MediaConvert\ValueObject\OutputChannelMapping;
use AsyncAws\MediaConvert\ValueObject\OutputDetail;
use AsyncAws\MediaConvert\ValueObject\OutputGroup;
use AsyncAws\MediaConvert\ValueObject\OutputGroupDetail;
use AsyncAws\MediaConvert\ValueObject\OutputGroupSettings;
use AsyncAws\MediaConvert\ValueObject\OutputSettings;
use AsyncAws\MediaConvert\ValueObject\PartnerWatermarking;
use AsyncAws\MediaConvert\ValueObject\ProresSettings;
use AsyncAws\MediaConvert\ValueObject\QueueTransition;
use AsyncAws\MediaConvert\ValueObject\Rectangle;
use AsyncAws\MediaConvert\ValueObject\RemixSettings;
use AsyncAws\MediaConvert\ValueObject\S3DestinationAccessControl;
use AsyncAws\MediaConvert\ValueObject\S3DestinationSettings;
use AsyncAws\MediaConvert\ValueObject\S3EncryptionSettings;
use AsyncAws\MediaConvert\ValueObject\SccDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\SpekeKeyProvider;
use AsyncAws\MediaConvert\ValueObject\SpekeKeyProviderCmaf;
use AsyncAws\MediaConvert\ValueObject\SrtDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\StaticKeyProvider;
use AsyncAws\MediaConvert\ValueObject\TeletextDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\TeletextSourceSettings;
use AsyncAws\MediaConvert\ValueObject\TimecodeBurnin;
use AsyncAws\MediaConvert\ValueObject\TimecodeConfig;
use AsyncAws\MediaConvert\ValueObject\TimedMetadataInsertion;
use AsyncAws\MediaConvert\ValueObject\Timing;
use AsyncAws\MediaConvert\ValueObject\TrackSourceSettings;
use AsyncAws\MediaConvert\ValueObject\TtmlDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\UncompressedSettings;
use AsyncAws\MediaConvert\ValueObject\Vc3Settings;
use AsyncAws\MediaConvert\ValueObject\VideoCodecSettings;
use AsyncAws\MediaConvert\ValueObject\VideoDescription;
use AsyncAws\MediaConvert\ValueObject\VideoDetail;
use AsyncAws\MediaConvert\ValueObject\VideoOverlay;
use AsyncAws\MediaConvert\ValueObject\VideoOverlayCrop;
use AsyncAws\MediaConvert\ValueObject\VideoOverlayInput;
use AsyncAws\MediaConvert\ValueObject\VideoOverlayInputClipping;
use AsyncAws\MediaConvert\ValueObject\VideoOverlayPosition;
use AsyncAws\MediaConvert\ValueObject\VideoOverlayTransition;
use AsyncAws\MediaConvert\ValueObject\VideoPreprocessor;
use AsyncAws\MediaConvert\ValueObject\VideoSelector;
use AsyncAws\MediaConvert\ValueObject\VorbisSettings;
use AsyncAws\MediaConvert\ValueObject\Vp8Settings;
use AsyncAws\MediaConvert\ValueObject\Vp9Settings;
use AsyncAws\MediaConvert\ValueObject\WarningGroup;
use AsyncAws\MediaConvert\ValueObject\WavSettings;
use AsyncAws\MediaConvert\ValueObject\WebvttDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\WebvttHlsSourceSettings;
use AsyncAws\MediaConvert\ValueObject\Xavc4kIntraCbgProfileSettings;
use AsyncAws\MediaConvert\ValueObject\Xavc4kIntraVbrProfileSettings;
use AsyncAws\MediaConvert\ValueObject\Xavc4kProfileSettings;
use AsyncAws\MediaConvert\ValueObject\XavcHdIntraCbgProfileSettings;
use AsyncAws\MediaConvert\ValueObject\XavcHdProfileSettings;
use AsyncAws\MediaConvert\ValueObject\XavcSettings;

/**
 * Successful get job requests will return an OK message and the job JSON.
 */
class GetJobResponse extends Result
{
    /**
     * Each job converts an input file into an output file or files. For more information, see the User Guide at
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/what-is.html.
     *
     * @var Job|null
     */
    private $job;

    public function getJob(): ?Job
    {
        $this->initialize();

        return $this->job;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->job = empty($data['job']) ? null : $this->populateResultJob($data['job']);
    }

    private function populateResultAacSettings(array $json): AacSettings
    {
        return new AacSettings([
            'AudioDescriptionBroadcasterMix' => isset($json['audioDescriptionBroadcasterMix']) ? (string) $json['audioDescriptionBroadcasterMix'] : null,
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'CodecProfile' => isset($json['codecProfile']) ? (string) $json['codecProfile'] : null,
            'CodingMode' => isset($json['codingMode']) ? (string) $json['codingMode'] : null,
            'RateControlMode' => isset($json['rateControlMode']) ? (string) $json['rateControlMode'] : null,
            'RawFormat' => isset($json['rawFormat']) ? (string) $json['rawFormat'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
            'Specification' => isset($json['specification']) ? (string) $json['specification'] : null,
            'VbrQuality' => isset($json['vbrQuality']) ? (string) $json['vbrQuality'] : null,
        ]);
    }

    private function populateResultAc3Settings(array $json): Ac3Settings
    {
        return new Ac3Settings([
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'BitstreamMode' => isset($json['bitstreamMode']) ? (string) $json['bitstreamMode'] : null,
            'CodingMode' => isset($json['codingMode']) ? (string) $json['codingMode'] : null,
            'Dialnorm' => isset($json['dialnorm']) ? (int) $json['dialnorm'] : null,
            'DynamicRangeCompressionLine' => isset($json['dynamicRangeCompressionLine']) ? (string) $json['dynamicRangeCompressionLine'] : null,
            'DynamicRangeCompressionProfile' => isset($json['dynamicRangeCompressionProfile']) ? (string) $json['dynamicRangeCompressionProfile'] : null,
            'DynamicRangeCompressionRf' => isset($json['dynamicRangeCompressionRf']) ? (string) $json['dynamicRangeCompressionRf'] : null,
            'LfeFilter' => isset($json['lfeFilter']) ? (string) $json['lfeFilter'] : null,
            'MetadataControl' => isset($json['metadataControl']) ? (string) $json['metadataControl'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
        ]);
    }

    private function populateResultAccelerationSettings(array $json): AccelerationSettings
    {
        return new AccelerationSettings([
            'Mode' => (string) $json['mode'],
        ]);
    }

    private function populateResultAdvancedInputFilterSettings(array $json): AdvancedInputFilterSettings
    {
        return new AdvancedInputFilterSettings([
            'AddTexture' => isset($json['addTexture']) ? (string) $json['addTexture'] : null,
            'Sharpening' => isset($json['sharpening']) ? (string) $json['sharpening'] : null,
        ]);
    }

    private function populateResultAiffSettings(array $json): AiffSettings
    {
        return new AiffSettings([
            'BitDepth' => isset($json['bitDepth']) ? (int) $json['bitDepth'] : null,
            'Channels' => isset($json['channels']) ? (int) $json['channels'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
        ]);
    }

    private function populateResultAllowedRenditionSize(array $json): AllowedRenditionSize
    {
        return new AllowedRenditionSize([
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'Required' => isset($json['required']) ? (string) $json['required'] : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
        ]);
    }

    private function populateResultAncillarySourceSettings(array $json): AncillarySourceSettings
    {
        return new AncillarySourceSettings([
            'Convert608To708' => isset($json['convert608To708']) ? (string) $json['convert608To708'] : null,
            'SourceAncillaryChannelNumber' => isset($json['sourceAncillaryChannelNumber']) ? (int) $json['sourceAncillaryChannelNumber'] : null,
            'TerminateCaptions' => isset($json['terminateCaptions']) ? (string) $json['terminateCaptions'] : null,
        ]);
    }

    private function populateResultAudioChannelTaggingSettings(array $json): AudioChannelTaggingSettings
    {
        return new AudioChannelTaggingSettings([
            'ChannelTag' => isset($json['channelTag']) ? (string) $json['channelTag'] : null,
            'ChannelTags' => !isset($json['channelTags']) ? null : $this->populateResult__listOfAudioChannelTag($json['channelTags']),
        ]);
    }

    private function populateResultAudioCodecSettings(array $json): AudioCodecSettings
    {
        return new AudioCodecSettings([
            'AacSettings' => empty($json['aacSettings']) ? null : $this->populateResultAacSettings($json['aacSettings']),
            'Ac3Settings' => empty($json['ac3Settings']) ? null : $this->populateResultAc3Settings($json['ac3Settings']),
            'AiffSettings' => empty($json['aiffSettings']) ? null : $this->populateResultAiffSettings($json['aiffSettings']),
            'Codec' => isset($json['codec']) ? (string) $json['codec'] : null,
            'Eac3AtmosSettings' => empty($json['eac3AtmosSettings']) ? null : $this->populateResultEac3AtmosSettings($json['eac3AtmosSettings']),
            'Eac3Settings' => empty($json['eac3Settings']) ? null : $this->populateResultEac3Settings($json['eac3Settings']),
            'FlacSettings' => empty($json['flacSettings']) ? null : $this->populateResultFlacSettings($json['flacSettings']),
            'Mp2Settings' => empty($json['mp2Settings']) ? null : $this->populateResultMp2Settings($json['mp2Settings']),
            'Mp3Settings' => empty($json['mp3Settings']) ? null : $this->populateResultMp3Settings($json['mp3Settings']),
            'OpusSettings' => empty($json['opusSettings']) ? null : $this->populateResultOpusSettings($json['opusSettings']),
            'VorbisSettings' => empty($json['vorbisSettings']) ? null : $this->populateResultVorbisSettings($json['vorbisSettings']),
            'WavSettings' => empty($json['wavSettings']) ? null : $this->populateResultWavSettings($json['wavSettings']),
        ]);
    }

    private function populateResultAudioDescription(array $json): AudioDescription
    {
        return new AudioDescription([
            'AudioChannelTaggingSettings' => empty($json['audioChannelTaggingSettings']) ? null : $this->populateResultAudioChannelTaggingSettings($json['audioChannelTaggingSettings']),
            'AudioNormalizationSettings' => empty($json['audioNormalizationSettings']) ? null : $this->populateResultAudioNormalizationSettings($json['audioNormalizationSettings']),
            'AudioSourceName' => isset($json['audioSourceName']) ? (string) $json['audioSourceName'] : null,
            'AudioType' => isset($json['audioType']) ? (int) $json['audioType'] : null,
            'AudioTypeControl' => isset($json['audioTypeControl']) ? (string) $json['audioTypeControl'] : null,
            'CodecSettings' => empty($json['codecSettings']) ? null : $this->populateResultAudioCodecSettings($json['codecSettings']),
            'CustomLanguageCode' => isset($json['customLanguageCode']) ? (string) $json['customLanguageCode'] : null,
            'LanguageCode' => isset($json['languageCode']) ? (string) $json['languageCode'] : null,
            'LanguageCodeControl' => isset($json['languageCodeControl']) ? (string) $json['languageCodeControl'] : null,
            'RemixSettings' => empty($json['remixSettings']) ? null : $this->populateResultRemixSettings($json['remixSettings']),
            'StreamName' => isset($json['streamName']) ? (string) $json['streamName'] : null,
        ]);
    }

    private function populateResultAudioNormalizationSettings(array $json): AudioNormalizationSettings
    {
        return new AudioNormalizationSettings([
            'Algorithm' => isset($json['algorithm']) ? (string) $json['algorithm'] : null,
            'AlgorithmControl' => isset($json['algorithmControl']) ? (string) $json['algorithmControl'] : null,
            'CorrectionGateLevel' => isset($json['correctionGateLevel']) ? (int) $json['correctionGateLevel'] : null,
            'LoudnessLogging' => isset($json['loudnessLogging']) ? (string) $json['loudnessLogging'] : null,
            'PeakCalculation' => isset($json['peakCalculation']) ? (string) $json['peakCalculation'] : null,
            'TargetLkfs' => isset($json['targetLkfs']) ? (float) $json['targetLkfs'] : null,
            'TruePeakLimiterThreshold' => isset($json['truePeakLimiterThreshold']) ? (float) $json['truePeakLimiterThreshold'] : null,
        ]);
    }

    private function populateResultAudioSelector(array $json): AudioSelector
    {
        return new AudioSelector([
            'AudioDurationCorrection' => isset($json['audioDurationCorrection']) ? (string) $json['audioDurationCorrection'] : null,
            'CustomLanguageCode' => isset($json['customLanguageCode']) ? (string) $json['customLanguageCode'] : null,
            'DefaultSelection' => isset($json['defaultSelection']) ? (string) $json['defaultSelection'] : null,
            'ExternalAudioFileInput' => isset($json['externalAudioFileInput']) ? (string) $json['externalAudioFileInput'] : null,
            'HlsRenditionGroupSettings' => empty($json['hlsRenditionGroupSettings']) ? null : $this->populateResultHlsRenditionGroupSettings($json['hlsRenditionGroupSettings']),
            'LanguageCode' => isset($json['languageCode']) ? (string) $json['languageCode'] : null,
            'Offset' => isset($json['offset']) ? (int) $json['offset'] : null,
            'Pids' => !isset($json['pids']) ? null : $this->populateResult__listOf__integerMin1Max2147483647($json['pids']),
            'ProgramSelection' => isset($json['programSelection']) ? (int) $json['programSelection'] : null,
            'RemixSettings' => empty($json['remixSettings']) ? null : $this->populateResultRemixSettings($json['remixSettings']),
            'SelectorType' => isset($json['selectorType']) ? (string) $json['selectorType'] : null,
            'Tracks' => !isset($json['tracks']) ? null : $this->populateResult__listOf__integerMin1Max2147483647($json['tracks']),
        ]);
    }

    private function populateResultAudioSelectorGroup(array $json): AudioSelectorGroup
    {
        return new AudioSelectorGroup([
            'AudioSelectorNames' => !isset($json['audioSelectorNames']) ? null : $this->populateResult__listOf__stringMin1($json['audioSelectorNames']),
        ]);
    }

    private function populateResultAutomatedAbrRule(array $json): AutomatedAbrRule
    {
        return new AutomatedAbrRule([
            'AllowedRenditions' => !isset($json['allowedRenditions']) ? null : $this->populateResult__listOfAllowedRenditionSize($json['allowedRenditions']),
            'ForceIncludeRenditions' => !isset($json['forceIncludeRenditions']) ? null : $this->populateResult__listOfForceIncludeRenditionSize($json['forceIncludeRenditions']),
            'MinBottomRenditionSize' => empty($json['minBottomRenditionSize']) ? null : $this->populateResultMinBottomRenditionSize($json['minBottomRenditionSize']),
            'MinTopRenditionSize' => empty($json['minTopRenditionSize']) ? null : $this->populateResultMinTopRenditionSize($json['minTopRenditionSize']),
            'Type' => isset($json['type']) ? (string) $json['type'] : null,
        ]);
    }

    private function populateResultAutomatedAbrSettings(array $json): AutomatedAbrSettings
    {
        return new AutomatedAbrSettings([
            'MaxAbrBitrate' => isset($json['maxAbrBitrate']) ? (int) $json['maxAbrBitrate'] : null,
            'MaxQualityLevel' => isset($json['maxQualityLevel']) ? (float) $json['maxQualityLevel'] : null,
            'MaxRenditions' => isset($json['maxRenditions']) ? (int) $json['maxRenditions'] : null,
            'MinAbrBitrate' => isset($json['minAbrBitrate']) ? (int) $json['minAbrBitrate'] : null,
            'Rules' => !isset($json['rules']) ? null : $this->populateResult__listOfAutomatedAbrRule($json['rules']),
        ]);
    }

    private function populateResultAutomatedEncodingSettings(array $json): AutomatedEncodingSettings
    {
        return new AutomatedEncodingSettings([
            'AbrSettings' => empty($json['abrSettings']) ? null : $this->populateResultAutomatedAbrSettings($json['abrSettings']),
        ]);
    }

    private function populateResultAv1QvbrSettings(array $json): Av1QvbrSettings
    {
        return new Av1QvbrSettings([
            'QvbrQualityLevel' => isset($json['qvbrQualityLevel']) ? (int) $json['qvbrQualityLevel'] : null,
            'QvbrQualityLevelFineTune' => isset($json['qvbrQualityLevelFineTune']) ? (float) $json['qvbrQualityLevelFineTune'] : null,
        ]);
    }

    private function populateResultAv1Settings(array $json): Av1Settings
    {
        return new Av1Settings([
            'AdaptiveQuantization' => isset($json['adaptiveQuantization']) ? (string) $json['adaptiveQuantization'] : null,
            'BitDepth' => isset($json['bitDepth']) ? (string) $json['bitDepth'] : null,
            'FilmGrainSynthesis' => isset($json['filmGrainSynthesis']) ? (string) $json['filmGrainSynthesis'] : null,
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'NumberBFramesBetweenReferenceFrames' => isset($json['numberBFramesBetweenReferenceFrames']) ? (int) $json['numberBFramesBetweenReferenceFrames'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'QvbrSettings' => empty($json['qvbrSettings']) ? null : $this->populateResultAv1QvbrSettings($json['qvbrSettings']),
            'RateControlMode' => isset($json['rateControlMode']) ? (string) $json['rateControlMode'] : null,
            'Slices' => isset($json['slices']) ? (int) $json['slices'] : null,
            'SpatialAdaptiveQuantization' => isset($json['spatialAdaptiveQuantization']) ? (string) $json['spatialAdaptiveQuantization'] : null,
        ]);
    }

    private function populateResultAvailBlanking(array $json): AvailBlanking
    {
        return new AvailBlanking([
            'AvailBlankingImage' => isset($json['availBlankingImage']) ? (string) $json['availBlankingImage'] : null,
        ]);
    }

    private function populateResultAvcIntraSettings(array $json): AvcIntraSettings
    {
        return new AvcIntraSettings([
            'AvcIntraClass' => isset($json['avcIntraClass']) ? (string) $json['avcIntraClass'] : null,
            'AvcIntraUhdSettings' => empty($json['avcIntraUhdSettings']) ? null : $this->populateResultAvcIntraUhdSettings($json['avcIntraUhdSettings']),
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (string) $json['interlaceMode'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (string) $json['scanTypeConversionMode'] : null,
            'SlowPal' => isset($json['slowPal']) ? (string) $json['slowPal'] : null,
            'Telecine' => isset($json['telecine']) ? (string) $json['telecine'] : null,
        ]);
    }

    private function populateResultAvcIntraUhdSettings(array $json): AvcIntraUhdSettings
    {
        return new AvcIntraUhdSettings([
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (string) $json['qualityTuningLevel'] : null,
        ]);
    }

    private function populateResultBandwidthReductionFilter(array $json): BandwidthReductionFilter
    {
        return new BandwidthReductionFilter([
            'Sharpening' => isset($json['sharpening']) ? (string) $json['sharpening'] : null,
            'Strength' => isset($json['strength']) ? (string) $json['strength'] : null,
        ]);
    }

    private function populateResultBurninDestinationSettings(array $json): BurninDestinationSettings
    {
        return new BurninDestinationSettings([
            'Alignment' => isset($json['alignment']) ? (string) $json['alignment'] : null,
            'ApplyFontColor' => isset($json['applyFontColor']) ? (string) $json['applyFontColor'] : null,
            'BackgroundColor' => isset($json['backgroundColor']) ? (string) $json['backgroundColor'] : null,
            'BackgroundOpacity' => isset($json['backgroundOpacity']) ? (int) $json['backgroundOpacity'] : null,
            'FallbackFont' => isset($json['fallbackFont']) ? (string) $json['fallbackFont'] : null,
            'FontColor' => isset($json['fontColor']) ? (string) $json['fontColor'] : null,
            'FontFileBold' => isset($json['fontFileBold']) ? (string) $json['fontFileBold'] : null,
            'FontFileBoldItalic' => isset($json['fontFileBoldItalic']) ? (string) $json['fontFileBoldItalic'] : null,
            'FontFileItalic' => isset($json['fontFileItalic']) ? (string) $json['fontFileItalic'] : null,
            'FontFileRegular' => isset($json['fontFileRegular']) ? (string) $json['fontFileRegular'] : null,
            'FontOpacity' => isset($json['fontOpacity']) ? (int) $json['fontOpacity'] : null,
            'FontResolution' => isset($json['fontResolution']) ? (int) $json['fontResolution'] : null,
            'FontScript' => isset($json['fontScript']) ? (string) $json['fontScript'] : null,
            'FontSize' => isset($json['fontSize']) ? (int) $json['fontSize'] : null,
            'HexFontColor' => isset($json['hexFontColor']) ? (string) $json['hexFontColor'] : null,
            'OutlineColor' => isset($json['outlineColor']) ? (string) $json['outlineColor'] : null,
            'OutlineSize' => isset($json['outlineSize']) ? (int) $json['outlineSize'] : null,
            'RemoveRubyReserveAttributes' => isset($json['removeRubyReserveAttributes']) ? (string) $json['removeRubyReserveAttributes'] : null,
            'ShadowColor' => isset($json['shadowColor']) ? (string) $json['shadowColor'] : null,
            'ShadowOpacity' => isset($json['shadowOpacity']) ? (int) $json['shadowOpacity'] : null,
            'ShadowXOffset' => isset($json['shadowXOffset']) ? (int) $json['shadowXOffset'] : null,
            'ShadowYOffset' => isset($json['shadowYOffset']) ? (int) $json['shadowYOffset'] : null,
            'StylePassthrough' => isset($json['stylePassthrough']) ? (string) $json['stylePassthrough'] : null,
            'TeletextSpacing' => isset($json['teletextSpacing']) ? (string) $json['teletextSpacing'] : null,
            'XPosition' => isset($json['xPosition']) ? (int) $json['xPosition'] : null,
            'YPosition' => isset($json['yPosition']) ? (int) $json['yPosition'] : null,
        ]);
    }

    private function populateResultCaptionDescription(array $json): CaptionDescription
    {
        return new CaptionDescription([
            'CaptionSelectorName' => isset($json['captionSelectorName']) ? (string) $json['captionSelectorName'] : null,
            'CustomLanguageCode' => isset($json['customLanguageCode']) ? (string) $json['customLanguageCode'] : null,
            'DestinationSettings' => empty($json['destinationSettings']) ? null : $this->populateResultCaptionDestinationSettings($json['destinationSettings']),
            'LanguageCode' => isset($json['languageCode']) ? (string) $json['languageCode'] : null,
            'LanguageDescription' => isset($json['languageDescription']) ? (string) $json['languageDescription'] : null,
        ]);
    }

    private function populateResultCaptionDestinationSettings(array $json): CaptionDestinationSettings
    {
        return new CaptionDestinationSettings([
            'BurninDestinationSettings' => empty($json['burninDestinationSettings']) ? null : $this->populateResultBurninDestinationSettings($json['burninDestinationSettings']),
            'DestinationType' => isset($json['destinationType']) ? (string) $json['destinationType'] : null,
            'DvbSubDestinationSettings' => empty($json['dvbSubDestinationSettings']) ? null : $this->populateResultDvbSubDestinationSettings($json['dvbSubDestinationSettings']),
            'EmbeddedDestinationSettings' => empty($json['embeddedDestinationSettings']) ? null : $this->populateResultEmbeddedDestinationSettings($json['embeddedDestinationSettings']),
            'ImscDestinationSettings' => empty($json['imscDestinationSettings']) ? null : $this->populateResultImscDestinationSettings($json['imscDestinationSettings']),
            'SccDestinationSettings' => empty($json['sccDestinationSettings']) ? null : $this->populateResultSccDestinationSettings($json['sccDestinationSettings']),
            'SrtDestinationSettings' => empty($json['srtDestinationSettings']) ? null : $this->populateResultSrtDestinationSettings($json['srtDestinationSettings']),
            'TeletextDestinationSettings' => empty($json['teletextDestinationSettings']) ? null : $this->populateResultTeletextDestinationSettings($json['teletextDestinationSettings']),
            'TtmlDestinationSettings' => empty($json['ttmlDestinationSettings']) ? null : $this->populateResultTtmlDestinationSettings($json['ttmlDestinationSettings']),
            'WebvttDestinationSettings' => empty($json['webvttDestinationSettings']) ? null : $this->populateResultWebvttDestinationSettings($json['webvttDestinationSettings']),
        ]);
    }

    private function populateResultCaptionSelector(array $json): CaptionSelector
    {
        return new CaptionSelector([
            'CustomLanguageCode' => isset($json['customLanguageCode']) ? (string) $json['customLanguageCode'] : null,
            'LanguageCode' => isset($json['languageCode']) ? (string) $json['languageCode'] : null,
            'SourceSettings' => empty($json['sourceSettings']) ? null : $this->populateResultCaptionSourceSettings($json['sourceSettings']),
        ]);
    }

    private function populateResultCaptionSourceFramerate(array $json): CaptionSourceFramerate
    {
        return new CaptionSourceFramerate([
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
        ]);
    }

    private function populateResultCaptionSourceSettings(array $json): CaptionSourceSettings
    {
        return new CaptionSourceSettings([
            'AncillarySourceSettings' => empty($json['ancillarySourceSettings']) ? null : $this->populateResultAncillarySourceSettings($json['ancillarySourceSettings']),
            'DvbSubSourceSettings' => empty($json['dvbSubSourceSettings']) ? null : $this->populateResultDvbSubSourceSettings($json['dvbSubSourceSettings']),
            'EmbeddedSourceSettings' => empty($json['embeddedSourceSettings']) ? null : $this->populateResultEmbeddedSourceSettings($json['embeddedSourceSettings']),
            'FileSourceSettings' => empty($json['fileSourceSettings']) ? null : $this->populateResultFileSourceSettings($json['fileSourceSettings']),
            'SourceType' => isset($json['sourceType']) ? (string) $json['sourceType'] : null,
            'TeletextSourceSettings' => empty($json['teletextSourceSettings']) ? null : $this->populateResultTeletextSourceSettings($json['teletextSourceSettings']),
            'TrackSourceSettings' => empty($json['trackSourceSettings']) ? null : $this->populateResultTrackSourceSettings($json['trackSourceSettings']),
            'WebvttHlsSourceSettings' => empty($json['webvttHlsSourceSettings']) ? null : $this->populateResultWebvttHlsSourceSettings($json['webvttHlsSourceSettings']),
        ]);
    }

    private function populateResultChannelMapping(array $json): ChannelMapping
    {
        return new ChannelMapping([
            'OutputChannels' => !isset($json['outputChannels']) ? null : $this->populateResult__listOfOutputChannelMapping($json['outputChannels']),
        ]);
    }

    private function populateResultClipLimits(array $json): ClipLimits
    {
        return new ClipLimits([
            'MaximumRGBTolerance' => isset($json['maximumRGBTolerance']) ? (int) $json['maximumRGBTolerance'] : null,
            'MaximumYUV' => isset($json['maximumYUV']) ? (int) $json['maximumYUV'] : null,
            'MinimumRGBTolerance' => isset($json['minimumRGBTolerance']) ? (int) $json['minimumRGBTolerance'] : null,
            'MinimumYUV' => isset($json['minimumYUV']) ? (int) $json['minimumYUV'] : null,
        ]);
    }

    private function populateResultCmafAdditionalManifest(array $json): CmafAdditionalManifest
    {
        return new CmafAdditionalManifest([
            'ManifestNameModifier' => isset($json['manifestNameModifier']) ? (string) $json['manifestNameModifier'] : null,
            'SelectedOutputs' => !isset($json['selectedOutputs']) ? null : $this->populateResult__listOf__stringMin1($json['selectedOutputs']),
        ]);
    }

    private function populateResultCmafEncryptionSettings(array $json): CmafEncryptionSettings
    {
        return new CmafEncryptionSettings([
            'ConstantInitializationVector' => isset($json['constantInitializationVector']) ? (string) $json['constantInitializationVector'] : null,
            'EncryptionMethod' => isset($json['encryptionMethod']) ? (string) $json['encryptionMethod'] : null,
            'InitializationVectorInManifest' => isset($json['initializationVectorInManifest']) ? (string) $json['initializationVectorInManifest'] : null,
            'SpekeKeyProvider' => empty($json['spekeKeyProvider']) ? null : $this->populateResultSpekeKeyProviderCmaf($json['spekeKeyProvider']),
            'StaticKeyProvider' => empty($json['staticKeyProvider']) ? null : $this->populateResultStaticKeyProvider($json['staticKeyProvider']),
            'Type' => isset($json['type']) ? (string) $json['type'] : null,
        ]);
    }

    private function populateResultCmafGroupSettings(array $json): CmafGroupSettings
    {
        return new CmafGroupSettings([
            'AdditionalManifests' => !isset($json['additionalManifests']) ? null : $this->populateResult__listOfCmafAdditionalManifest($json['additionalManifests']),
            'BaseUrl' => isset($json['baseUrl']) ? (string) $json['baseUrl'] : null,
            'ClientCache' => isset($json['clientCache']) ? (string) $json['clientCache'] : null,
            'CodecSpecification' => isset($json['codecSpecification']) ? (string) $json['codecSpecification'] : null,
            'DashIFrameTrickPlayNameModifier' => isset($json['dashIFrameTrickPlayNameModifier']) ? (string) $json['dashIFrameTrickPlayNameModifier'] : null,
            'DashManifestStyle' => isset($json['dashManifestStyle']) ? (string) $json['dashManifestStyle'] : null,
            'Destination' => isset($json['destination']) ? (string) $json['destination'] : null,
            'DestinationSettings' => empty($json['destinationSettings']) ? null : $this->populateResultDestinationSettings($json['destinationSettings']),
            'Encryption' => empty($json['encryption']) ? null : $this->populateResultCmafEncryptionSettings($json['encryption']),
            'FragmentLength' => isset($json['fragmentLength']) ? (int) $json['fragmentLength'] : null,
            'ImageBasedTrickPlay' => isset($json['imageBasedTrickPlay']) ? (string) $json['imageBasedTrickPlay'] : null,
            'ImageBasedTrickPlaySettings' => empty($json['imageBasedTrickPlaySettings']) ? null : $this->populateResultCmafImageBasedTrickPlaySettings($json['imageBasedTrickPlaySettings']),
            'ManifestCompression' => isset($json['manifestCompression']) ? (string) $json['manifestCompression'] : null,
            'ManifestDurationFormat' => isset($json['manifestDurationFormat']) ? (string) $json['manifestDurationFormat'] : null,
            'MinBufferTime' => isset($json['minBufferTime']) ? (int) $json['minBufferTime'] : null,
            'MinFinalSegmentLength' => isset($json['minFinalSegmentLength']) ? (float) $json['minFinalSegmentLength'] : null,
            'MpdManifestBandwidthType' => isset($json['mpdManifestBandwidthType']) ? (string) $json['mpdManifestBandwidthType'] : null,
            'MpdProfile' => isset($json['mpdProfile']) ? (string) $json['mpdProfile'] : null,
            'PtsOffsetHandlingForBFrames' => isset($json['ptsOffsetHandlingForBFrames']) ? (string) $json['ptsOffsetHandlingForBFrames'] : null,
            'SegmentControl' => isset($json['segmentControl']) ? (string) $json['segmentControl'] : null,
            'SegmentLength' => isset($json['segmentLength']) ? (int) $json['segmentLength'] : null,
            'SegmentLengthControl' => isset($json['segmentLengthControl']) ? (string) $json['segmentLengthControl'] : null,
            'StreamInfResolution' => isset($json['streamInfResolution']) ? (string) $json['streamInfResolution'] : null,
            'TargetDurationCompatibilityMode' => isset($json['targetDurationCompatibilityMode']) ? (string) $json['targetDurationCompatibilityMode'] : null,
            'VideoCompositionOffsets' => isset($json['videoCompositionOffsets']) ? (string) $json['videoCompositionOffsets'] : null,
            'WriteDashManifest' => isset($json['writeDashManifest']) ? (string) $json['writeDashManifest'] : null,
            'WriteHlsManifest' => isset($json['writeHlsManifest']) ? (string) $json['writeHlsManifest'] : null,
            'WriteSegmentTimelineInRepresentation' => isset($json['writeSegmentTimelineInRepresentation']) ? (string) $json['writeSegmentTimelineInRepresentation'] : null,
        ]);
    }

    private function populateResultCmafImageBasedTrickPlaySettings(array $json): CmafImageBasedTrickPlaySettings
    {
        return new CmafImageBasedTrickPlaySettings([
            'IntervalCadence' => isset($json['intervalCadence']) ? (string) $json['intervalCadence'] : null,
            'ThumbnailHeight' => isset($json['thumbnailHeight']) ? (int) $json['thumbnailHeight'] : null,
            'ThumbnailInterval' => isset($json['thumbnailInterval']) ? (float) $json['thumbnailInterval'] : null,
            'ThumbnailWidth' => isset($json['thumbnailWidth']) ? (int) $json['thumbnailWidth'] : null,
            'TileHeight' => isset($json['tileHeight']) ? (int) $json['tileHeight'] : null,
            'TileWidth' => isset($json['tileWidth']) ? (int) $json['tileWidth'] : null,
        ]);
    }

    private function populateResultCmfcSettings(array $json): CmfcSettings
    {
        return new CmfcSettings([
            'AudioDuration' => isset($json['audioDuration']) ? (string) $json['audioDuration'] : null,
            'AudioGroupId' => isset($json['audioGroupId']) ? (string) $json['audioGroupId'] : null,
            'AudioRenditionSets' => isset($json['audioRenditionSets']) ? (string) $json['audioRenditionSets'] : null,
            'AudioTrackType' => isset($json['audioTrackType']) ? (string) $json['audioTrackType'] : null,
            'DescriptiveVideoServiceFlag' => isset($json['descriptiveVideoServiceFlag']) ? (string) $json['descriptiveVideoServiceFlag'] : null,
            'IFrameOnlyManifest' => isset($json['iFrameOnlyManifest']) ? (string) $json['iFrameOnlyManifest'] : null,
            'KlvMetadata' => isset($json['klvMetadata']) ? (string) $json['klvMetadata'] : null,
            'ManifestMetadataSignaling' => isset($json['manifestMetadataSignaling']) ? (string) $json['manifestMetadataSignaling'] : null,
            'Scte35Esam' => isset($json['scte35Esam']) ? (string) $json['scte35Esam'] : null,
            'Scte35Source' => isset($json['scte35Source']) ? (string) $json['scte35Source'] : null,
            'TimedMetadata' => isset($json['timedMetadata']) ? (string) $json['timedMetadata'] : null,
            'TimedMetadataBoxVersion' => isset($json['timedMetadataBoxVersion']) ? (string) $json['timedMetadataBoxVersion'] : null,
            'TimedMetadataSchemeIdUri' => isset($json['timedMetadataSchemeIdUri']) ? (string) $json['timedMetadataSchemeIdUri'] : null,
            'TimedMetadataValue' => isset($json['timedMetadataValue']) ? (string) $json['timedMetadataValue'] : null,
        ]);
    }

    private function populateResultColorConversion3DLUTSetting(array $json): ColorConversion3DLUTSetting
    {
        return new ColorConversion3DLUTSetting([
            'FileInput' => isset($json['fileInput']) ? (string) $json['fileInput'] : null,
            'InputColorSpace' => isset($json['inputColorSpace']) ? (string) $json['inputColorSpace'] : null,
            'InputMasteringLuminance' => isset($json['inputMasteringLuminance']) ? (int) $json['inputMasteringLuminance'] : null,
            'OutputColorSpace' => isset($json['outputColorSpace']) ? (string) $json['outputColorSpace'] : null,
            'OutputMasteringLuminance' => isset($json['outputMasteringLuminance']) ? (int) $json['outputMasteringLuminance'] : null,
        ]);
    }

    private function populateResultColorCorrector(array $json): ColorCorrector
    {
        return new ColorCorrector([
            'Brightness' => isset($json['brightness']) ? (int) $json['brightness'] : null,
            'ClipLimits' => empty($json['clipLimits']) ? null : $this->populateResultClipLimits($json['clipLimits']),
            'ColorSpaceConversion' => isset($json['colorSpaceConversion']) ? (string) $json['colorSpaceConversion'] : null,
            'Contrast' => isset($json['contrast']) ? (int) $json['contrast'] : null,
            'Hdr10Metadata' => empty($json['hdr10Metadata']) ? null : $this->populateResultHdr10Metadata($json['hdr10Metadata']),
            'HdrToSdrToneMapper' => isset($json['hdrToSdrToneMapper']) ? (string) $json['hdrToSdrToneMapper'] : null,
            'Hue' => isset($json['hue']) ? (int) $json['hue'] : null,
            'MaxLuminance' => isset($json['maxLuminance']) ? (int) $json['maxLuminance'] : null,
            'SampleRangeConversion' => isset($json['sampleRangeConversion']) ? (string) $json['sampleRangeConversion'] : null,
            'Saturation' => isset($json['saturation']) ? (int) $json['saturation'] : null,
            'SdrReferenceWhiteLevel' => isset($json['sdrReferenceWhiteLevel']) ? (int) $json['sdrReferenceWhiteLevel'] : null,
        ]);
    }

    private function populateResultContainerSettings(array $json): ContainerSettings
    {
        return new ContainerSettings([
            'CmfcSettings' => empty($json['cmfcSettings']) ? null : $this->populateResultCmfcSettings($json['cmfcSettings']),
            'Container' => isset($json['container']) ? (string) $json['container'] : null,
            'F4vSettings' => empty($json['f4vSettings']) ? null : $this->populateResultF4vSettings($json['f4vSettings']),
            'M2tsSettings' => empty($json['m2tsSettings']) ? null : $this->populateResultM2tsSettings($json['m2tsSettings']),
            'M3u8Settings' => empty($json['m3u8Settings']) ? null : $this->populateResultM3u8Settings($json['m3u8Settings']),
            'MovSettings' => empty($json['movSettings']) ? null : $this->populateResultMovSettings($json['movSettings']),
            'Mp4Settings' => empty($json['mp4Settings']) ? null : $this->populateResultMp4Settings($json['mp4Settings']),
            'MpdSettings' => empty($json['mpdSettings']) ? null : $this->populateResultMpdSettings($json['mpdSettings']),
            'MxfSettings' => empty($json['mxfSettings']) ? null : $this->populateResultMxfSettings($json['mxfSettings']),
        ]);
    }

    private function populateResultDashAdditionalManifest(array $json): DashAdditionalManifest
    {
        return new DashAdditionalManifest([
            'ManifestNameModifier' => isset($json['manifestNameModifier']) ? (string) $json['manifestNameModifier'] : null,
            'SelectedOutputs' => !isset($json['selectedOutputs']) ? null : $this->populateResult__listOf__stringMin1($json['selectedOutputs']),
        ]);
    }

    private function populateResultDashIsoEncryptionSettings(array $json): DashIsoEncryptionSettings
    {
        return new DashIsoEncryptionSettings([
            'PlaybackDeviceCompatibility' => isset($json['playbackDeviceCompatibility']) ? (string) $json['playbackDeviceCompatibility'] : null,
            'SpekeKeyProvider' => empty($json['spekeKeyProvider']) ? null : $this->populateResultSpekeKeyProvider($json['spekeKeyProvider']),
        ]);
    }

    private function populateResultDashIsoGroupSettings(array $json): DashIsoGroupSettings
    {
        return new DashIsoGroupSettings([
            'AdditionalManifests' => !isset($json['additionalManifests']) ? null : $this->populateResult__listOfDashAdditionalManifest($json['additionalManifests']),
            'AudioChannelConfigSchemeIdUri' => isset($json['audioChannelConfigSchemeIdUri']) ? (string) $json['audioChannelConfigSchemeIdUri'] : null,
            'BaseUrl' => isset($json['baseUrl']) ? (string) $json['baseUrl'] : null,
            'DashIFrameTrickPlayNameModifier' => isset($json['dashIFrameTrickPlayNameModifier']) ? (string) $json['dashIFrameTrickPlayNameModifier'] : null,
            'DashManifestStyle' => isset($json['dashManifestStyle']) ? (string) $json['dashManifestStyle'] : null,
            'Destination' => isset($json['destination']) ? (string) $json['destination'] : null,
            'DestinationSettings' => empty($json['destinationSettings']) ? null : $this->populateResultDestinationSettings($json['destinationSettings']),
            'Encryption' => empty($json['encryption']) ? null : $this->populateResultDashIsoEncryptionSettings($json['encryption']),
            'FragmentLength' => isset($json['fragmentLength']) ? (int) $json['fragmentLength'] : null,
            'HbbtvCompliance' => isset($json['hbbtvCompliance']) ? (string) $json['hbbtvCompliance'] : null,
            'ImageBasedTrickPlay' => isset($json['imageBasedTrickPlay']) ? (string) $json['imageBasedTrickPlay'] : null,
            'ImageBasedTrickPlaySettings' => empty($json['imageBasedTrickPlaySettings']) ? null : $this->populateResultDashIsoImageBasedTrickPlaySettings($json['imageBasedTrickPlaySettings']),
            'MinBufferTime' => isset($json['minBufferTime']) ? (int) $json['minBufferTime'] : null,
            'MinFinalSegmentLength' => isset($json['minFinalSegmentLength']) ? (float) $json['minFinalSegmentLength'] : null,
            'MpdManifestBandwidthType' => isset($json['mpdManifestBandwidthType']) ? (string) $json['mpdManifestBandwidthType'] : null,
            'MpdProfile' => isset($json['mpdProfile']) ? (string) $json['mpdProfile'] : null,
            'PtsOffsetHandlingForBFrames' => isset($json['ptsOffsetHandlingForBFrames']) ? (string) $json['ptsOffsetHandlingForBFrames'] : null,
            'SegmentControl' => isset($json['segmentControl']) ? (string) $json['segmentControl'] : null,
            'SegmentLength' => isset($json['segmentLength']) ? (int) $json['segmentLength'] : null,
            'SegmentLengthControl' => isset($json['segmentLengthControl']) ? (string) $json['segmentLengthControl'] : null,
            'VideoCompositionOffsets' => isset($json['videoCompositionOffsets']) ? (string) $json['videoCompositionOffsets'] : null,
            'WriteSegmentTimelineInRepresentation' => isset($json['writeSegmentTimelineInRepresentation']) ? (string) $json['writeSegmentTimelineInRepresentation'] : null,
        ]);
    }

    private function populateResultDashIsoImageBasedTrickPlaySettings(array $json): DashIsoImageBasedTrickPlaySettings
    {
        return new DashIsoImageBasedTrickPlaySettings([
            'IntervalCadence' => isset($json['intervalCadence']) ? (string) $json['intervalCadence'] : null,
            'ThumbnailHeight' => isset($json['thumbnailHeight']) ? (int) $json['thumbnailHeight'] : null,
            'ThumbnailInterval' => isset($json['thumbnailInterval']) ? (float) $json['thumbnailInterval'] : null,
            'ThumbnailWidth' => isset($json['thumbnailWidth']) ? (int) $json['thumbnailWidth'] : null,
            'TileHeight' => isset($json['tileHeight']) ? (int) $json['tileHeight'] : null,
            'TileWidth' => isset($json['tileWidth']) ? (int) $json['tileWidth'] : null,
        ]);
    }

    private function populateResultDeinterlacer(array $json): Deinterlacer
    {
        return new Deinterlacer([
            'Algorithm' => isset($json['algorithm']) ? (string) $json['algorithm'] : null,
            'Control' => isset($json['control']) ? (string) $json['control'] : null,
            'Mode' => isset($json['mode']) ? (string) $json['mode'] : null,
        ]);
    }

    private function populateResultDestinationSettings(array $json): DestinationSettings
    {
        return new DestinationSettings([
            'S3Settings' => empty($json['s3Settings']) ? null : $this->populateResultS3DestinationSettings($json['s3Settings']),
        ]);
    }

    private function populateResultDolbyVision(array $json): DolbyVision
    {
        return new DolbyVision([
            'L6Metadata' => empty($json['l6Metadata']) ? null : $this->populateResultDolbyVisionLevel6Metadata($json['l6Metadata']),
            'L6Mode' => isset($json['l6Mode']) ? (string) $json['l6Mode'] : null,
            'Mapping' => isset($json['mapping']) ? (string) $json['mapping'] : null,
            'Profile' => isset($json['profile']) ? (string) $json['profile'] : null,
        ]);
    }

    private function populateResultDolbyVisionLevel6Metadata(array $json): DolbyVisionLevel6Metadata
    {
        return new DolbyVisionLevel6Metadata([
            'MaxCll' => isset($json['maxCll']) ? (int) $json['maxCll'] : null,
            'MaxFall' => isset($json['maxFall']) ? (int) $json['maxFall'] : null,
        ]);
    }

    private function populateResultDvbNitSettings(array $json): DvbNitSettings
    {
        return new DvbNitSettings([
            'NetworkId' => isset($json['networkId']) ? (int) $json['networkId'] : null,
            'NetworkName' => isset($json['networkName']) ? (string) $json['networkName'] : null,
            'NitInterval' => isset($json['nitInterval']) ? (int) $json['nitInterval'] : null,
        ]);
    }

    private function populateResultDvbSdtSettings(array $json): DvbSdtSettings
    {
        return new DvbSdtSettings([
            'OutputSdt' => isset($json['outputSdt']) ? (string) $json['outputSdt'] : null,
            'SdtInterval' => isset($json['sdtInterval']) ? (int) $json['sdtInterval'] : null,
            'ServiceName' => isset($json['serviceName']) ? (string) $json['serviceName'] : null,
            'ServiceProviderName' => isset($json['serviceProviderName']) ? (string) $json['serviceProviderName'] : null,
        ]);
    }

    private function populateResultDvbSubDestinationSettings(array $json): DvbSubDestinationSettings
    {
        return new DvbSubDestinationSettings([
            'Alignment' => isset($json['alignment']) ? (string) $json['alignment'] : null,
            'ApplyFontColor' => isset($json['applyFontColor']) ? (string) $json['applyFontColor'] : null,
            'BackgroundColor' => isset($json['backgroundColor']) ? (string) $json['backgroundColor'] : null,
            'BackgroundOpacity' => isset($json['backgroundOpacity']) ? (int) $json['backgroundOpacity'] : null,
            'DdsHandling' => isset($json['ddsHandling']) ? (string) $json['ddsHandling'] : null,
            'DdsXCoordinate' => isset($json['ddsXCoordinate']) ? (int) $json['ddsXCoordinate'] : null,
            'DdsYCoordinate' => isset($json['ddsYCoordinate']) ? (int) $json['ddsYCoordinate'] : null,
            'FallbackFont' => isset($json['fallbackFont']) ? (string) $json['fallbackFont'] : null,
            'FontColor' => isset($json['fontColor']) ? (string) $json['fontColor'] : null,
            'FontFileBold' => isset($json['fontFileBold']) ? (string) $json['fontFileBold'] : null,
            'FontFileBoldItalic' => isset($json['fontFileBoldItalic']) ? (string) $json['fontFileBoldItalic'] : null,
            'FontFileItalic' => isset($json['fontFileItalic']) ? (string) $json['fontFileItalic'] : null,
            'FontFileRegular' => isset($json['fontFileRegular']) ? (string) $json['fontFileRegular'] : null,
            'FontOpacity' => isset($json['fontOpacity']) ? (int) $json['fontOpacity'] : null,
            'FontResolution' => isset($json['fontResolution']) ? (int) $json['fontResolution'] : null,
            'FontScript' => isset($json['fontScript']) ? (string) $json['fontScript'] : null,
            'FontSize' => isset($json['fontSize']) ? (int) $json['fontSize'] : null,
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'HexFontColor' => isset($json['hexFontColor']) ? (string) $json['hexFontColor'] : null,
            'OutlineColor' => isset($json['outlineColor']) ? (string) $json['outlineColor'] : null,
            'OutlineSize' => isset($json['outlineSize']) ? (int) $json['outlineSize'] : null,
            'ShadowColor' => isset($json['shadowColor']) ? (string) $json['shadowColor'] : null,
            'ShadowOpacity' => isset($json['shadowOpacity']) ? (int) $json['shadowOpacity'] : null,
            'ShadowXOffset' => isset($json['shadowXOffset']) ? (int) $json['shadowXOffset'] : null,
            'ShadowYOffset' => isset($json['shadowYOffset']) ? (int) $json['shadowYOffset'] : null,
            'StylePassthrough' => isset($json['stylePassthrough']) ? (string) $json['stylePassthrough'] : null,
            'SubtitlingType' => isset($json['subtitlingType']) ? (string) $json['subtitlingType'] : null,
            'TeletextSpacing' => isset($json['teletextSpacing']) ? (string) $json['teletextSpacing'] : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
            'XPosition' => isset($json['xPosition']) ? (int) $json['xPosition'] : null,
            'YPosition' => isset($json['yPosition']) ? (int) $json['yPosition'] : null,
        ]);
    }

    private function populateResultDvbSubSourceSettings(array $json): DvbSubSourceSettings
    {
        return new DvbSubSourceSettings([
            'Pid' => isset($json['pid']) ? (int) $json['pid'] : null,
        ]);
    }

    private function populateResultDvbTdtSettings(array $json): DvbTdtSettings
    {
        return new DvbTdtSettings([
            'TdtInterval' => isset($json['tdtInterval']) ? (int) $json['tdtInterval'] : null,
        ]);
    }

    private function populateResultDynamicAudioSelector(array $json): DynamicAudioSelector
    {
        return new DynamicAudioSelector([
            'AudioDurationCorrection' => isset($json['audioDurationCorrection']) ? (string) $json['audioDurationCorrection'] : null,
            'ExternalAudioFileInput' => isset($json['externalAudioFileInput']) ? (string) $json['externalAudioFileInput'] : null,
            'LanguageCode' => isset($json['languageCode']) ? (string) $json['languageCode'] : null,
            'Offset' => isset($json['offset']) ? (int) $json['offset'] : null,
            'SelectorType' => isset($json['selectorType']) ? (string) $json['selectorType'] : null,
        ]);
    }

    private function populateResultEac3AtmosSettings(array $json): Eac3AtmosSettings
    {
        return new Eac3AtmosSettings([
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'BitstreamMode' => isset($json['bitstreamMode']) ? (string) $json['bitstreamMode'] : null,
            'CodingMode' => isset($json['codingMode']) ? (string) $json['codingMode'] : null,
            'DialogueIntelligence' => isset($json['dialogueIntelligence']) ? (string) $json['dialogueIntelligence'] : null,
            'DownmixControl' => isset($json['downmixControl']) ? (string) $json['downmixControl'] : null,
            'DynamicRangeCompressionLine' => isset($json['dynamicRangeCompressionLine']) ? (string) $json['dynamicRangeCompressionLine'] : null,
            'DynamicRangeCompressionRf' => isset($json['dynamicRangeCompressionRf']) ? (string) $json['dynamicRangeCompressionRf'] : null,
            'DynamicRangeControl' => isset($json['dynamicRangeControl']) ? (string) $json['dynamicRangeControl'] : null,
            'LoRoCenterMixLevel' => isset($json['loRoCenterMixLevel']) ? (float) $json['loRoCenterMixLevel'] : null,
            'LoRoSurroundMixLevel' => isset($json['loRoSurroundMixLevel']) ? (float) $json['loRoSurroundMixLevel'] : null,
            'LtRtCenterMixLevel' => isset($json['ltRtCenterMixLevel']) ? (float) $json['ltRtCenterMixLevel'] : null,
            'LtRtSurroundMixLevel' => isset($json['ltRtSurroundMixLevel']) ? (float) $json['ltRtSurroundMixLevel'] : null,
            'MeteringMode' => isset($json['meteringMode']) ? (string) $json['meteringMode'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
            'SpeechThreshold' => isset($json['speechThreshold']) ? (int) $json['speechThreshold'] : null,
            'StereoDownmix' => isset($json['stereoDownmix']) ? (string) $json['stereoDownmix'] : null,
            'SurroundExMode' => isset($json['surroundExMode']) ? (string) $json['surroundExMode'] : null,
        ]);
    }

    private function populateResultEac3Settings(array $json): Eac3Settings
    {
        return new Eac3Settings([
            'AttenuationControl' => isset($json['attenuationControl']) ? (string) $json['attenuationControl'] : null,
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'BitstreamMode' => isset($json['bitstreamMode']) ? (string) $json['bitstreamMode'] : null,
            'CodingMode' => isset($json['codingMode']) ? (string) $json['codingMode'] : null,
            'DcFilter' => isset($json['dcFilter']) ? (string) $json['dcFilter'] : null,
            'Dialnorm' => isset($json['dialnorm']) ? (int) $json['dialnorm'] : null,
            'DynamicRangeCompressionLine' => isset($json['dynamicRangeCompressionLine']) ? (string) $json['dynamicRangeCompressionLine'] : null,
            'DynamicRangeCompressionRf' => isset($json['dynamicRangeCompressionRf']) ? (string) $json['dynamicRangeCompressionRf'] : null,
            'LfeControl' => isset($json['lfeControl']) ? (string) $json['lfeControl'] : null,
            'LfeFilter' => isset($json['lfeFilter']) ? (string) $json['lfeFilter'] : null,
            'LoRoCenterMixLevel' => isset($json['loRoCenterMixLevel']) ? (float) $json['loRoCenterMixLevel'] : null,
            'LoRoSurroundMixLevel' => isset($json['loRoSurroundMixLevel']) ? (float) $json['loRoSurroundMixLevel'] : null,
            'LtRtCenterMixLevel' => isset($json['ltRtCenterMixLevel']) ? (float) $json['ltRtCenterMixLevel'] : null,
            'LtRtSurroundMixLevel' => isset($json['ltRtSurroundMixLevel']) ? (float) $json['ltRtSurroundMixLevel'] : null,
            'MetadataControl' => isset($json['metadataControl']) ? (string) $json['metadataControl'] : null,
            'PassthroughControl' => isset($json['passthroughControl']) ? (string) $json['passthroughControl'] : null,
            'PhaseControl' => isset($json['phaseControl']) ? (string) $json['phaseControl'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
            'StereoDownmix' => isset($json['stereoDownmix']) ? (string) $json['stereoDownmix'] : null,
            'SurroundExMode' => isset($json['surroundExMode']) ? (string) $json['surroundExMode'] : null,
            'SurroundMode' => isset($json['surroundMode']) ? (string) $json['surroundMode'] : null,
        ]);
    }

    private function populateResultEmbeddedDestinationSettings(array $json): EmbeddedDestinationSettings
    {
        return new EmbeddedDestinationSettings([
            'Destination608ChannelNumber' => isset($json['destination608ChannelNumber']) ? (int) $json['destination608ChannelNumber'] : null,
            'Destination708ServiceNumber' => isset($json['destination708ServiceNumber']) ? (int) $json['destination708ServiceNumber'] : null,
        ]);
    }

    private function populateResultEmbeddedSourceSettings(array $json): EmbeddedSourceSettings
    {
        return new EmbeddedSourceSettings([
            'Convert608To708' => isset($json['convert608To708']) ? (string) $json['convert608To708'] : null,
            'Source608ChannelNumber' => isset($json['source608ChannelNumber']) ? (int) $json['source608ChannelNumber'] : null,
            'Source608TrackNumber' => isset($json['source608TrackNumber']) ? (int) $json['source608TrackNumber'] : null,
            'TerminateCaptions' => isset($json['terminateCaptions']) ? (string) $json['terminateCaptions'] : null,
        ]);
    }

    private function populateResultEncryptionContractConfiguration(array $json): EncryptionContractConfiguration
    {
        return new EncryptionContractConfiguration([
            'SpekeAudioPreset' => isset($json['spekeAudioPreset']) ? (string) $json['spekeAudioPreset'] : null,
            'SpekeVideoPreset' => isset($json['spekeVideoPreset']) ? (string) $json['spekeVideoPreset'] : null,
        ]);
    }

    private function populateResultEsamManifestConfirmConditionNotification(array $json): EsamManifestConfirmConditionNotification
    {
        return new EsamManifestConfirmConditionNotification([
            'MccXml' => isset($json['mccXml']) ? (string) $json['mccXml'] : null,
        ]);
    }

    private function populateResultEsamSettings(array $json): EsamSettings
    {
        return new EsamSettings([
            'ManifestConfirmConditionNotification' => empty($json['manifestConfirmConditionNotification']) ? null : $this->populateResultEsamManifestConfirmConditionNotification($json['manifestConfirmConditionNotification']),
            'ResponseSignalPreroll' => isset($json['responseSignalPreroll']) ? (int) $json['responseSignalPreroll'] : null,
            'SignalProcessingNotification' => empty($json['signalProcessingNotification']) ? null : $this->populateResultEsamSignalProcessingNotification($json['signalProcessingNotification']),
        ]);
    }

    private function populateResultEsamSignalProcessingNotification(array $json): EsamSignalProcessingNotification
    {
        return new EsamSignalProcessingNotification([
            'SccXml' => isset($json['sccXml']) ? (string) $json['sccXml'] : null,
        ]);
    }

    private function populateResultExtendedDataServices(array $json): ExtendedDataServices
    {
        return new ExtendedDataServices([
            'CopyProtectionAction' => isset($json['copyProtectionAction']) ? (string) $json['copyProtectionAction'] : null,
            'VchipAction' => isset($json['vchipAction']) ? (string) $json['vchipAction'] : null,
        ]);
    }

    private function populateResultF4vSettings(array $json): F4vSettings
    {
        return new F4vSettings([
            'MoovPlacement' => isset($json['moovPlacement']) ? (string) $json['moovPlacement'] : null,
        ]);
    }

    private function populateResultFileGroupSettings(array $json): FileGroupSettings
    {
        return new FileGroupSettings([
            'Destination' => isset($json['destination']) ? (string) $json['destination'] : null,
            'DestinationSettings' => empty($json['destinationSettings']) ? null : $this->populateResultDestinationSettings($json['destinationSettings']),
        ]);
    }

    private function populateResultFileSourceSettings(array $json): FileSourceSettings
    {
        return new FileSourceSettings([
            'ByteRateLimit' => isset($json['byteRateLimit']) ? (string) $json['byteRateLimit'] : null,
            'Convert608To708' => isset($json['convert608To708']) ? (string) $json['convert608To708'] : null,
            'ConvertPaintToPop' => isset($json['convertPaintToPop']) ? (string) $json['convertPaintToPop'] : null,
            'Framerate' => empty($json['framerate']) ? null : $this->populateResultCaptionSourceFramerate($json['framerate']),
            'SourceFile' => isset($json['sourceFile']) ? (string) $json['sourceFile'] : null,
            'TimeDelta' => isset($json['timeDelta']) ? (int) $json['timeDelta'] : null,
            'TimeDeltaUnits' => isset($json['timeDeltaUnits']) ? (string) $json['timeDeltaUnits'] : null,
            'UpconvertSTLToTeletext' => isset($json['upconvertSTLToTeletext']) ? (string) $json['upconvertSTLToTeletext'] : null,
        ]);
    }

    private function populateResultFlacSettings(array $json): FlacSettings
    {
        return new FlacSettings([
            'BitDepth' => isset($json['bitDepth']) ? (int) $json['bitDepth'] : null,
            'Channels' => isset($json['channels']) ? (int) $json['channels'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
        ]);
    }

    private function populateResultForceIncludeRenditionSize(array $json): ForceIncludeRenditionSize
    {
        return new ForceIncludeRenditionSize([
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
        ]);
    }

    private function populateResultFrameCaptureSettings(array $json): FrameCaptureSettings
    {
        return new FrameCaptureSettings([
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'MaxCaptures' => isset($json['maxCaptures']) ? (int) $json['maxCaptures'] : null,
            'Quality' => isset($json['quality']) ? (int) $json['quality'] : null,
        ]);
    }

    private function populateResultGifSettings(array $json): GifSettings
    {
        return new GifSettings([
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
        ]);
    }

    private function populateResultH264QvbrSettings(array $json): H264QvbrSettings
    {
        return new H264QvbrSettings([
            'MaxAverageBitrate' => isset($json['maxAverageBitrate']) ? (int) $json['maxAverageBitrate'] : null,
            'QvbrQualityLevel' => isset($json['qvbrQualityLevel']) ? (int) $json['qvbrQualityLevel'] : null,
            'QvbrQualityLevelFineTune' => isset($json['qvbrQualityLevelFineTune']) ? (float) $json['qvbrQualityLevelFineTune'] : null,
        ]);
    }

    private function populateResultH264Settings(array $json): H264Settings
    {
        return new H264Settings([
            'AdaptiveQuantization' => isset($json['adaptiveQuantization']) ? (string) $json['adaptiveQuantization'] : null,
            'BandwidthReductionFilter' => empty($json['bandwidthReductionFilter']) ? null : $this->populateResultBandwidthReductionFilter($json['bandwidthReductionFilter']),
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'CodecLevel' => isset($json['codecLevel']) ? (string) $json['codecLevel'] : null,
            'CodecProfile' => isset($json['codecProfile']) ? (string) $json['codecProfile'] : null,
            'DynamicSubGop' => isset($json['dynamicSubGop']) ? (string) $json['dynamicSubGop'] : null,
            'EndOfStreamMarkers' => isset($json['endOfStreamMarkers']) ? (string) $json['endOfStreamMarkers'] : null,
            'EntropyEncoding' => isset($json['entropyEncoding']) ? (string) $json['entropyEncoding'] : null,
            'FieldEncoding' => isset($json['fieldEncoding']) ? (string) $json['fieldEncoding'] : null,
            'FlickerAdaptiveQuantization' => isset($json['flickerAdaptiveQuantization']) ? (string) $json['flickerAdaptiveQuantization'] : null,
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopBReference' => isset($json['gopBReference']) ? (string) $json['gopBReference'] : null,
            'GopClosedCadence' => isset($json['gopClosedCadence']) ? (int) $json['gopClosedCadence'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'GopSizeUnits' => isset($json['gopSizeUnits']) ? (string) $json['gopSizeUnits'] : null,
            'HrdBufferFinalFillPercentage' => isset($json['hrdBufferFinalFillPercentage']) ? (int) $json['hrdBufferFinalFillPercentage'] : null,
            'HrdBufferInitialFillPercentage' => isset($json['hrdBufferInitialFillPercentage']) ? (int) $json['hrdBufferInitialFillPercentage'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (string) $json['interlaceMode'] : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'MinIInterval' => isset($json['minIInterval']) ? (int) $json['minIInterval'] : null,
            'NumberBFramesBetweenReferenceFrames' => isset($json['numberBFramesBetweenReferenceFrames']) ? (int) $json['numberBFramesBetweenReferenceFrames'] : null,
            'NumberReferenceFrames' => isset($json['numberReferenceFrames']) ? (int) $json['numberReferenceFrames'] : null,
            'ParControl' => isset($json['parControl']) ? (string) $json['parControl'] : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (string) $json['qualityTuningLevel'] : null,
            'QvbrSettings' => empty($json['qvbrSettings']) ? null : $this->populateResultH264QvbrSettings($json['qvbrSettings']),
            'RateControlMode' => isset($json['rateControlMode']) ? (string) $json['rateControlMode'] : null,
            'RepeatPps' => isset($json['repeatPps']) ? (string) $json['repeatPps'] : null,
            'SaliencyAwareEncoding' => isset($json['saliencyAwareEncoding']) ? (string) $json['saliencyAwareEncoding'] : null,
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (string) $json['scanTypeConversionMode'] : null,
            'SceneChangeDetect' => isset($json['sceneChangeDetect']) ? (string) $json['sceneChangeDetect'] : null,
            'Slices' => isset($json['slices']) ? (int) $json['slices'] : null,
            'SlowPal' => isset($json['slowPal']) ? (string) $json['slowPal'] : null,
            'Softness' => isset($json['softness']) ? (int) $json['softness'] : null,
            'SpatialAdaptiveQuantization' => isset($json['spatialAdaptiveQuantization']) ? (string) $json['spatialAdaptiveQuantization'] : null,
            'Syntax' => isset($json['syntax']) ? (string) $json['syntax'] : null,
            'Telecine' => isset($json['telecine']) ? (string) $json['telecine'] : null,
            'TemporalAdaptiveQuantization' => isset($json['temporalAdaptiveQuantization']) ? (string) $json['temporalAdaptiveQuantization'] : null,
            'UnregisteredSeiTimecode' => isset($json['unregisteredSeiTimecode']) ? (string) $json['unregisteredSeiTimecode'] : null,
            'WriteMp4PackagingType' => isset($json['writeMp4PackagingType']) ? (string) $json['writeMp4PackagingType'] : null,
        ]);
    }

    private function populateResultH265QvbrSettings(array $json): H265QvbrSettings
    {
        return new H265QvbrSettings([
            'MaxAverageBitrate' => isset($json['maxAverageBitrate']) ? (int) $json['maxAverageBitrate'] : null,
            'QvbrQualityLevel' => isset($json['qvbrQualityLevel']) ? (int) $json['qvbrQualityLevel'] : null,
            'QvbrQualityLevelFineTune' => isset($json['qvbrQualityLevelFineTune']) ? (float) $json['qvbrQualityLevelFineTune'] : null,
        ]);
    }

    private function populateResultH265Settings(array $json): H265Settings
    {
        return new H265Settings([
            'AdaptiveQuantization' => isset($json['adaptiveQuantization']) ? (string) $json['adaptiveQuantization'] : null,
            'AlternateTransferFunctionSei' => isset($json['alternateTransferFunctionSei']) ? (string) $json['alternateTransferFunctionSei'] : null,
            'BandwidthReductionFilter' => empty($json['bandwidthReductionFilter']) ? null : $this->populateResultBandwidthReductionFilter($json['bandwidthReductionFilter']),
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'CodecLevel' => isset($json['codecLevel']) ? (string) $json['codecLevel'] : null,
            'CodecProfile' => isset($json['codecProfile']) ? (string) $json['codecProfile'] : null,
            'Deblocking' => isset($json['deblocking']) ? (string) $json['deblocking'] : null,
            'DynamicSubGop' => isset($json['dynamicSubGop']) ? (string) $json['dynamicSubGop'] : null,
            'EndOfStreamMarkers' => isset($json['endOfStreamMarkers']) ? (string) $json['endOfStreamMarkers'] : null,
            'FlickerAdaptiveQuantization' => isset($json['flickerAdaptiveQuantization']) ? (string) $json['flickerAdaptiveQuantization'] : null,
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopBReference' => isset($json['gopBReference']) ? (string) $json['gopBReference'] : null,
            'GopClosedCadence' => isset($json['gopClosedCadence']) ? (int) $json['gopClosedCadence'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'GopSizeUnits' => isset($json['gopSizeUnits']) ? (string) $json['gopSizeUnits'] : null,
            'HrdBufferFinalFillPercentage' => isset($json['hrdBufferFinalFillPercentage']) ? (int) $json['hrdBufferFinalFillPercentage'] : null,
            'HrdBufferInitialFillPercentage' => isset($json['hrdBufferInitialFillPercentage']) ? (int) $json['hrdBufferInitialFillPercentage'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (string) $json['interlaceMode'] : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'MinIInterval' => isset($json['minIInterval']) ? (int) $json['minIInterval'] : null,
            'NumberBFramesBetweenReferenceFrames' => isset($json['numberBFramesBetweenReferenceFrames']) ? (int) $json['numberBFramesBetweenReferenceFrames'] : null,
            'NumberReferenceFrames' => isset($json['numberReferenceFrames']) ? (int) $json['numberReferenceFrames'] : null,
            'ParControl' => isset($json['parControl']) ? (string) $json['parControl'] : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (string) $json['qualityTuningLevel'] : null,
            'QvbrSettings' => empty($json['qvbrSettings']) ? null : $this->populateResultH265QvbrSettings($json['qvbrSettings']),
            'RateControlMode' => isset($json['rateControlMode']) ? (string) $json['rateControlMode'] : null,
            'SampleAdaptiveOffsetFilterMode' => isset($json['sampleAdaptiveOffsetFilterMode']) ? (string) $json['sampleAdaptiveOffsetFilterMode'] : null,
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (string) $json['scanTypeConversionMode'] : null,
            'SceneChangeDetect' => isset($json['sceneChangeDetect']) ? (string) $json['sceneChangeDetect'] : null,
            'Slices' => isset($json['slices']) ? (int) $json['slices'] : null,
            'SlowPal' => isset($json['slowPal']) ? (string) $json['slowPal'] : null,
            'SpatialAdaptiveQuantization' => isset($json['spatialAdaptiveQuantization']) ? (string) $json['spatialAdaptiveQuantization'] : null,
            'Telecine' => isset($json['telecine']) ? (string) $json['telecine'] : null,
            'TemporalAdaptiveQuantization' => isset($json['temporalAdaptiveQuantization']) ? (string) $json['temporalAdaptiveQuantization'] : null,
            'TemporalIds' => isset($json['temporalIds']) ? (string) $json['temporalIds'] : null,
            'Tiles' => isset($json['tiles']) ? (string) $json['tiles'] : null,
            'UnregisteredSeiTimecode' => isset($json['unregisteredSeiTimecode']) ? (string) $json['unregisteredSeiTimecode'] : null,
            'WriteMp4PackagingType' => isset($json['writeMp4PackagingType']) ? (string) $json['writeMp4PackagingType'] : null,
        ]);
    }

    private function populateResultHdr10Metadata(array $json): Hdr10Metadata
    {
        return new Hdr10Metadata([
            'BluePrimaryX' => isset($json['bluePrimaryX']) ? (int) $json['bluePrimaryX'] : null,
            'BluePrimaryY' => isset($json['bluePrimaryY']) ? (int) $json['bluePrimaryY'] : null,
            'GreenPrimaryX' => isset($json['greenPrimaryX']) ? (int) $json['greenPrimaryX'] : null,
            'GreenPrimaryY' => isset($json['greenPrimaryY']) ? (int) $json['greenPrimaryY'] : null,
            'MaxContentLightLevel' => isset($json['maxContentLightLevel']) ? (int) $json['maxContentLightLevel'] : null,
            'MaxFrameAverageLightLevel' => isset($json['maxFrameAverageLightLevel']) ? (int) $json['maxFrameAverageLightLevel'] : null,
            'MaxLuminance' => isset($json['maxLuminance']) ? (int) $json['maxLuminance'] : null,
            'MinLuminance' => isset($json['minLuminance']) ? (int) $json['minLuminance'] : null,
            'RedPrimaryX' => isset($json['redPrimaryX']) ? (int) $json['redPrimaryX'] : null,
            'RedPrimaryY' => isset($json['redPrimaryY']) ? (int) $json['redPrimaryY'] : null,
            'WhitePointX' => isset($json['whitePointX']) ? (int) $json['whitePointX'] : null,
            'WhitePointY' => isset($json['whitePointY']) ? (int) $json['whitePointY'] : null,
        ]);
    }

    private function populateResultHdr10Plus(array $json): Hdr10Plus
    {
        return new Hdr10Plus([
            'MasteringMonitorNits' => isset($json['masteringMonitorNits']) ? (int) $json['masteringMonitorNits'] : null,
            'TargetMonitorNits' => isset($json['targetMonitorNits']) ? (int) $json['targetMonitorNits'] : null,
        ]);
    }

    private function populateResultHlsAdditionalManifest(array $json): HlsAdditionalManifest
    {
        return new HlsAdditionalManifest([
            'ManifestNameModifier' => isset($json['manifestNameModifier']) ? (string) $json['manifestNameModifier'] : null,
            'SelectedOutputs' => !isset($json['selectedOutputs']) ? null : $this->populateResult__listOf__stringMin1($json['selectedOutputs']),
        ]);
    }

    private function populateResultHlsCaptionLanguageMapping(array $json): HlsCaptionLanguageMapping
    {
        return new HlsCaptionLanguageMapping([
            'CaptionChannel' => isset($json['captionChannel']) ? (int) $json['captionChannel'] : null,
            'CustomLanguageCode' => isset($json['customLanguageCode']) ? (string) $json['customLanguageCode'] : null,
            'LanguageCode' => isset($json['languageCode']) ? (string) $json['languageCode'] : null,
            'LanguageDescription' => isset($json['languageDescription']) ? (string) $json['languageDescription'] : null,
        ]);
    }

    private function populateResultHlsEncryptionSettings(array $json): HlsEncryptionSettings
    {
        return new HlsEncryptionSettings([
            'ConstantInitializationVector' => isset($json['constantInitializationVector']) ? (string) $json['constantInitializationVector'] : null,
            'EncryptionMethod' => isset($json['encryptionMethod']) ? (string) $json['encryptionMethod'] : null,
            'InitializationVectorInManifest' => isset($json['initializationVectorInManifest']) ? (string) $json['initializationVectorInManifest'] : null,
            'OfflineEncrypted' => isset($json['offlineEncrypted']) ? (string) $json['offlineEncrypted'] : null,
            'SpekeKeyProvider' => empty($json['spekeKeyProvider']) ? null : $this->populateResultSpekeKeyProvider($json['spekeKeyProvider']),
            'StaticKeyProvider' => empty($json['staticKeyProvider']) ? null : $this->populateResultStaticKeyProvider($json['staticKeyProvider']),
            'Type' => isset($json['type']) ? (string) $json['type'] : null,
        ]);
    }

    private function populateResultHlsGroupSettings(array $json): HlsGroupSettings
    {
        return new HlsGroupSettings([
            'AdMarkers' => !isset($json['adMarkers']) ? null : $this->populateResult__listOfHlsAdMarkers($json['adMarkers']),
            'AdditionalManifests' => !isset($json['additionalManifests']) ? null : $this->populateResult__listOfHlsAdditionalManifest($json['additionalManifests']),
            'AudioOnlyHeader' => isset($json['audioOnlyHeader']) ? (string) $json['audioOnlyHeader'] : null,
            'BaseUrl' => isset($json['baseUrl']) ? (string) $json['baseUrl'] : null,
            'CaptionLanguageMappings' => !isset($json['captionLanguageMappings']) ? null : $this->populateResult__listOfHlsCaptionLanguageMapping($json['captionLanguageMappings']),
            'CaptionLanguageSetting' => isset($json['captionLanguageSetting']) ? (string) $json['captionLanguageSetting'] : null,
            'CaptionSegmentLengthControl' => isset($json['captionSegmentLengthControl']) ? (string) $json['captionSegmentLengthControl'] : null,
            'ClientCache' => isset($json['clientCache']) ? (string) $json['clientCache'] : null,
            'CodecSpecification' => isset($json['codecSpecification']) ? (string) $json['codecSpecification'] : null,
            'Destination' => isset($json['destination']) ? (string) $json['destination'] : null,
            'DestinationSettings' => empty($json['destinationSettings']) ? null : $this->populateResultDestinationSettings($json['destinationSettings']),
            'DirectoryStructure' => isset($json['directoryStructure']) ? (string) $json['directoryStructure'] : null,
            'Encryption' => empty($json['encryption']) ? null : $this->populateResultHlsEncryptionSettings($json['encryption']),
            'ImageBasedTrickPlay' => isset($json['imageBasedTrickPlay']) ? (string) $json['imageBasedTrickPlay'] : null,
            'ImageBasedTrickPlaySettings' => empty($json['imageBasedTrickPlaySettings']) ? null : $this->populateResultHlsImageBasedTrickPlaySettings($json['imageBasedTrickPlaySettings']),
            'ManifestCompression' => isset($json['manifestCompression']) ? (string) $json['manifestCompression'] : null,
            'ManifestDurationFormat' => isset($json['manifestDurationFormat']) ? (string) $json['manifestDurationFormat'] : null,
            'MinFinalSegmentLength' => isset($json['minFinalSegmentLength']) ? (float) $json['minFinalSegmentLength'] : null,
            'MinSegmentLength' => isset($json['minSegmentLength']) ? (int) $json['minSegmentLength'] : null,
            'OutputSelection' => isset($json['outputSelection']) ? (string) $json['outputSelection'] : null,
            'ProgramDateTime' => isset($json['programDateTime']) ? (string) $json['programDateTime'] : null,
            'ProgramDateTimePeriod' => isset($json['programDateTimePeriod']) ? (int) $json['programDateTimePeriod'] : null,
            'ProgressiveWriteHlsManifest' => isset($json['progressiveWriteHlsManifest']) ? (string) $json['progressiveWriteHlsManifest'] : null,
            'SegmentControl' => isset($json['segmentControl']) ? (string) $json['segmentControl'] : null,
            'SegmentLength' => isset($json['segmentLength']) ? (int) $json['segmentLength'] : null,
            'SegmentLengthControl' => isset($json['segmentLengthControl']) ? (string) $json['segmentLengthControl'] : null,
            'SegmentsPerSubdirectory' => isset($json['segmentsPerSubdirectory']) ? (int) $json['segmentsPerSubdirectory'] : null,
            'StreamInfResolution' => isset($json['streamInfResolution']) ? (string) $json['streamInfResolution'] : null,
            'TargetDurationCompatibilityMode' => isset($json['targetDurationCompatibilityMode']) ? (string) $json['targetDurationCompatibilityMode'] : null,
            'TimedMetadataId3Frame' => isset($json['timedMetadataId3Frame']) ? (string) $json['timedMetadataId3Frame'] : null,
            'TimedMetadataId3Period' => isset($json['timedMetadataId3Period']) ? (int) $json['timedMetadataId3Period'] : null,
            'TimestampDeltaMilliseconds' => isset($json['timestampDeltaMilliseconds']) ? (int) $json['timestampDeltaMilliseconds'] : null,
        ]);
    }

    private function populateResultHlsImageBasedTrickPlaySettings(array $json): HlsImageBasedTrickPlaySettings
    {
        return new HlsImageBasedTrickPlaySettings([
            'IntervalCadence' => isset($json['intervalCadence']) ? (string) $json['intervalCadence'] : null,
            'ThumbnailHeight' => isset($json['thumbnailHeight']) ? (int) $json['thumbnailHeight'] : null,
            'ThumbnailInterval' => isset($json['thumbnailInterval']) ? (float) $json['thumbnailInterval'] : null,
            'ThumbnailWidth' => isset($json['thumbnailWidth']) ? (int) $json['thumbnailWidth'] : null,
            'TileHeight' => isset($json['tileHeight']) ? (int) $json['tileHeight'] : null,
            'TileWidth' => isset($json['tileWidth']) ? (int) $json['tileWidth'] : null,
        ]);
    }

    private function populateResultHlsRenditionGroupSettings(array $json): HlsRenditionGroupSettings
    {
        return new HlsRenditionGroupSettings([
            'RenditionGroupId' => isset($json['renditionGroupId']) ? (string) $json['renditionGroupId'] : null,
            'RenditionLanguageCode' => isset($json['renditionLanguageCode']) ? (string) $json['renditionLanguageCode'] : null,
            'RenditionName' => isset($json['renditionName']) ? (string) $json['renditionName'] : null,
        ]);
    }

    private function populateResultHlsSettings(array $json): HlsSettings
    {
        return new HlsSettings([
            'AudioGroupId' => isset($json['audioGroupId']) ? (string) $json['audioGroupId'] : null,
            'AudioOnlyContainer' => isset($json['audioOnlyContainer']) ? (string) $json['audioOnlyContainer'] : null,
            'AudioRenditionSets' => isset($json['audioRenditionSets']) ? (string) $json['audioRenditionSets'] : null,
            'AudioTrackType' => isset($json['audioTrackType']) ? (string) $json['audioTrackType'] : null,
            'DescriptiveVideoServiceFlag' => isset($json['descriptiveVideoServiceFlag']) ? (string) $json['descriptiveVideoServiceFlag'] : null,
            'IFrameOnlyManifest' => isset($json['iFrameOnlyManifest']) ? (string) $json['iFrameOnlyManifest'] : null,
            'SegmentModifier' => isset($json['segmentModifier']) ? (string) $json['segmentModifier'] : null,
        ]);
    }

    private function populateResultHopDestination(array $json): HopDestination
    {
        return new HopDestination([
            'Priority' => isset($json['priority']) ? (int) $json['priority'] : null,
            'Queue' => isset($json['queue']) ? (string) $json['queue'] : null,
            'WaitMinutes' => isset($json['waitMinutes']) ? (int) $json['waitMinutes'] : null,
        ]);
    }

    private function populateResultId3Insertion(array $json): Id3Insertion
    {
        return new Id3Insertion([
            'Id3' => isset($json['id3']) ? (string) $json['id3'] : null,
            'Timecode' => isset($json['timecode']) ? (string) $json['timecode'] : null,
        ]);
    }

    private function populateResultImageInserter(array $json): ImageInserter
    {
        return new ImageInserter([
            'InsertableImages' => !isset($json['insertableImages']) ? null : $this->populateResult__listOfInsertableImage($json['insertableImages']),
            'SdrReferenceWhiteLevel' => isset($json['sdrReferenceWhiteLevel']) ? (int) $json['sdrReferenceWhiteLevel'] : null,
        ]);
    }

    private function populateResultImscDestinationSettings(array $json): ImscDestinationSettings
    {
        return new ImscDestinationSettings([
            'Accessibility' => isset($json['accessibility']) ? (string) $json['accessibility'] : null,
            'StylePassthrough' => isset($json['stylePassthrough']) ? (string) $json['stylePassthrough'] : null,
        ]);
    }

    private function populateResultInput(array $json): Input
    {
        return new Input([
            'AdvancedInputFilter' => isset($json['advancedInputFilter']) ? (string) $json['advancedInputFilter'] : null,
            'AdvancedInputFilterSettings' => empty($json['advancedInputFilterSettings']) ? null : $this->populateResultAdvancedInputFilterSettings($json['advancedInputFilterSettings']),
            'AudioSelectorGroups' => !isset($json['audioSelectorGroups']) ? null : $this->populateResult__mapOfAudioSelectorGroup($json['audioSelectorGroups']),
            'AudioSelectors' => !isset($json['audioSelectors']) ? null : $this->populateResult__mapOfAudioSelector($json['audioSelectors']),
            'CaptionSelectors' => !isset($json['captionSelectors']) ? null : $this->populateResult__mapOfCaptionSelector($json['captionSelectors']),
            'Crop' => empty($json['crop']) ? null : $this->populateResultRectangle($json['crop']),
            'DeblockFilter' => isset($json['deblockFilter']) ? (string) $json['deblockFilter'] : null,
            'DecryptionSettings' => empty($json['decryptionSettings']) ? null : $this->populateResultInputDecryptionSettings($json['decryptionSettings']),
            'DenoiseFilter' => isset($json['denoiseFilter']) ? (string) $json['denoiseFilter'] : null,
            'DolbyVisionMetadataXml' => isset($json['dolbyVisionMetadataXml']) ? (string) $json['dolbyVisionMetadataXml'] : null,
            'DynamicAudioSelectors' => !isset($json['dynamicAudioSelectors']) ? null : $this->populateResult__mapOfDynamicAudioSelector($json['dynamicAudioSelectors']),
            'FileInput' => isset($json['fileInput']) ? (string) $json['fileInput'] : null,
            'FilterEnable' => isset($json['filterEnable']) ? (string) $json['filterEnable'] : null,
            'FilterStrength' => isset($json['filterStrength']) ? (int) $json['filterStrength'] : null,
            'ImageInserter' => empty($json['imageInserter']) ? null : $this->populateResultImageInserter($json['imageInserter']),
            'InputClippings' => !isset($json['inputClippings']) ? null : $this->populateResult__listOfInputClipping($json['inputClippings']),
            'InputScanType' => isset($json['inputScanType']) ? (string) $json['inputScanType'] : null,
            'Position' => empty($json['position']) ? null : $this->populateResultRectangle($json['position']),
            'ProgramNumber' => isset($json['programNumber']) ? (int) $json['programNumber'] : null,
            'PsiControl' => isset($json['psiControl']) ? (string) $json['psiControl'] : null,
            'SupplementalImps' => !isset($json['supplementalImps']) ? null : $this->populateResult__listOf__stringPatternS3ASSETMAPXml($json['supplementalImps']),
            'TimecodeSource' => isset($json['timecodeSource']) ? (string) $json['timecodeSource'] : null,
            'TimecodeStart' => isset($json['timecodeStart']) ? (string) $json['timecodeStart'] : null,
            'VideoGenerator' => empty($json['videoGenerator']) ? null : $this->populateResultInputVideoGenerator($json['videoGenerator']),
            'VideoOverlays' => !isset($json['videoOverlays']) ? null : $this->populateResult__listOfVideoOverlay($json['videoOverlays']),
            'VideoSelector' => empty($json['videoSelector']) ? null : $this->populateResultVideoSelector($json['videoSelector']),
        ]);
    }

    private function populateResultInputClipping(array $json): InputClipping
    {
        return new InputClipping([
            'EndTimecode' => isset($json['endTimecode']) ? (string) $json['endTimecode'] : null,
            'StartTimecode' => isset($json['startTimecode']) ? (string) $json['startTimecode'] : null,
        ]);
    }

    private function populateResultInputDecryptionSettings(array $json): InputDecryptionSettings
    {
        return new InputDecryptionSettings([
            'DecryptionMode' => isset($json['decryptionMode']) ? (string) $json['decryptionMode'] : null,
            'EncryptedDecryptionKey' => isset($json['encryptedDecryptionKey']) ? (string) $json['encryptedDecryptionKey'] : null,
            'InitializationVector' => isset($json['initializationVector']) ? (string) $json['initializationVector'] : null,
            'KmsKeyRegion' => isset($json['kmsKeyRegion']) ? (string) $json['kmsKeyRegion'] : null,
        ]);
    }

    private function populateResultInputVideoGenerator(array $json): InputVideoGenerator
    {
        return new InputVideoGenerator([
            'Channels' => isset($json['channels']) ? (int) $json['channels'] : null,
            'Duration' => isset($json['duration']) ? (int) $json['duration'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
        ]);
    }

    private function populateResultInsertableImage(array $json): InsertableImage
    {
        return new InsertableImage([
            'Duration' => isset($json['duration']) ? (int) $json['duration'] : null,
            'FadeIn' => isset($json['fadeIn']) ? (int) $json['fadeIn'] : null,
            'FadeOut' => isset($json['fadeOut']) ? (int) $json['fadeOut'] : null,
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'ImageInserterInput' => isset($json['imageInserterInput']) ? (string) $json['imageInserterInput'] : null,
            'ImageX' => isset($json['imageX']) ? (int) $json['imageX'] : null,
            'ImageY' => isset($json['imageY']) ? (int) $json['imageY'] : null,
            'Layer' => isset($json['layer']) ? (int) $json['layer'] : null,
            'Opacity' => isset($json['opacity']) ? (int) $json['opacity'] : null,
            'StartTime' => isset($json['startTime']) ? (string) $json['startTime'] : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
        ]);
    }

    private function populateResultJob(array $json): Job
    {
        return new Job([
            'AccelerationSettings' => empty($json['accelerationSettings']) ? null : $this->populateResultAccelerationSettings($json['accelerationSettings']),
            'AccelerationStatus' => isset($json['accelerationStatus']) ? (string) $json['accelerationStatus'] : null,
            'Arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'BillingTagsSource' => isset($json['billingTagsSource']) ? (string) $json['billingTagsSource'] : null,
            'ClientRequestToken' => isset($json['clientRequestToken']) ? (string) $json['clientRequestToken'] : null,
            'CreatedAt' => isset($json['createdAt']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['createdAt']))) ? $d : null,
            'CurrentPhase' => isset($json['currentPhase']) ? (string) $json['currentPhase'] : null,
            'ErrorCode' => isset($json['errorCode']) ? (int) $json['errorCode'] : null,
            'ErrorMessage' => isset($json['errorMessage']) ? (string) $json['errorMessage'] : null,
            'HopDestinations' => !isset($json['hopDestinations']) ? null : $this->populateResult__listOfHopDestination($json['hopDestinations']),
            'Id' => isset($json['id']) ? (string) $json['id'] : null,
            'JobEngineVersionRequested' => isset($json['jobEngineVersionRequested']) ? (string) $json['jobEngineVersionRequested'] : null,
            'JobEngineVersionUsed' => isset($json['jobEngineVersionUsed']) ? (string) $json['jobEngineVersionUsed'] : null,
            'JobPercentComplete' => isset($json['jobPercentComplete']) ? (int) $json['jobPercentComplete'] : null,
            'JobTemplate' => isset($json['jobTemplate']) ? (string) $json['jobTemplate'] : null,
            'Messages' => empty($json['messages']) ? null : $this->populateResultJobMessages($json['messages']),
            'OutputGroupDetails' => !isset($json['outputGroupDetails']) ? null : $this->populateResult__listOfOutputGroupDetail($json['outputGroupDetails']),
            'Priority' => isset($json['priority']) ? (int) $json['priority'] : null,
            'Queue' => isset($json['queue']) ? (string) $json['queue'] : null,
            'QueueTransitions' => !isset($json['queueTransitions']) ? null : $this->populateResult__listOfQueueTransition($json['queueTransitions']),
            'RetryCount' => isset($json['retryCount']) ? (int) $json['retryCount'] : null,
            'Role' => (string) $json['role'],
            'Settings' => $this->populateResultJobSettings($json['settings']),
            'SimulateReservedQueue' => isset($json['simulateReservedQueue']) ? (string) $json['simulateReservedQueue'] : null,
            'Status' => isset($json['status']) ? (string) $json['status'] : null,
            'StatusUpdateInterval' => isset($json['statusUpdateInterval']) ? (string) $json['statusUpdateInterval'] : null,
            'Timing' => empty($json['timing']) ? null : $this->populateResultTiming($json['timing']),
            'UserMetadata' => !isset($json['userMetadata']) ? null : $this->populateResult__mapOf__string($json['userMetadata']),
            'Warnings' => !isset($json['warnings']) ? null : $this->populateResult__listOfWarningGroup($json['warnings']),
        ]);
    }

    private function populateResultJobMessages(array $json): JobMessages
    {
        return new JobMessages([
            'Info' => !isset($json['info']) ? null : $this->populateResult__listOf__string($json['info']),
            'Warning' => !isset($json['warning']) ? null : $this->populateResult__listOf__string($json['warning']),
        ]);
    }

    private function populateResultJobSettings(array $json): JobSettings
    {
        return new JobSettings([
            'AdAvailOffset' => isset($json['adAvailOffset']) ? (int) $json['adAvailOffset'] : null,
            'AvailBlanking' => empty($json['availBlanking']) ? null : $this->populateResultAvailBlanking($json['availBlanking']),
            'ColorConversion3DLUTSettings' => !isset($json['colorConversion3DLUTSettings']) ? null : $this->populateResult__listOfColorConversion3DLUTSetting($json['colorConversion3DLUTSettings']),
            'Esam' => empty($json['esam']) ? null : $this->populateResultEsamSettings($json['esam']),
            'ExtendedDataServices' => empty($json['extendedDataServices']) ? null : $this->populateResultExtendedDataServices($json['extendedDataServices']),
            'FollowSource' => isset($json['followSource']) ? (int) $json['followSource'] : null,
            'Inputs' => !isset($json['inputs']) ? null : $this->populateResult__listOfInput($json['inputs']),
            'KantarWatermark' => empty($json['kantarWatermark']) ? null : $this->populateResultKantarWatermarkSettings($json['kantarWatermark']),
            'MotionImageInserter' => empty($json['motionImageInserter']) ? null : $this->populateResultMotionImageInserter($json['motionImageInserter']),
            'NielsenConfiguration' => empty($json['nielsenConfiguration']) ? null : $this->populateResultNielsenConfiguration($json['nielsenConfiguration']),
            'NielsenNonLinearWatermark' => empty($json['nielsenNonLinearWatermark']) ? null : $this->populateResultNielsenNonLinearWatermarkSettings($json['nielsenNonLinearWatermark']),
            'OutputGroups' => !isset($json['outputGroups']) ? null : $this->populateResult__listOfOutputGroup($json['outputGroups']),
            'TimecodeConfig' => empty($json['timecodeConfig']) ? null : $this->populateResultTimecodeConfig($json['timecodeConfig']),
            'TimedMetadataInsertion' => empty($json['timedMetadataInsertion']) ? null : $this->populateResultTimedMetadataInsertion($json['timedMetadataInsertion']),
        ]);
    }

    private function populateResultKantarWatermarkSettings(array $json): KantarWatermarkSettings
    {
        return new KantarWatermarkSettings([
            'ChannelName' => isset($json['channelName']) ? (string) $json['channelName'] : null,
            'ContentReference' => isset($json['contentReference']) ? (string) $json['contentReference'] : null,
            'CredentialsSecretName' => isset($json['credentialsSecretName']) ? (string) $json['credentialsSecretName'] : null,
            'FileOffset' => isset($json['fileOffset']) ? (float) $json['fileOffset'] : null,
            'KantarLicenseId' => isset($json['kantarLicenseId']) ? (int) $json['kantarLicenseId'] : null,
            'KantarServerUrl' => isset($json['kantarServerUrl']) ? (string) $json['kantarServerUrl'] : null,
            'LogDestination' => isset($json['logDestination']) ? (string) $json['logDestination'] : null,
            'Metadata3' => isset($json['metadata3']) ? (string) $json['metadata3'] : null,
            'Metadata4' => isset($json['metadata4']) ? (string) $json['metadata4'] : null,
            'Metadata5' => isset($json['metadata5']) ? (string) $json['metadata5'] : null,
            'Metadata6' => isset($json['metadata6']) ? (string) $json['metadata6'] : null,
            'Metadata7' => isset($json['metadata7']) ? (string) $json['metadata7'] : null,
            'Metadata8' => isset($json['metadata8']) ? (string) $json['metadata8'] : null,
        ]);
    }

    private function populateResultM2tsScte35Esam(array $json): M2tsScte35Esam
    {
        return new M2tsScte35Esam([
            'Scte35EsamPid' => isset($json['scte35EsamPid']) ? (int) $json['scte35EsamPid'] : null,
        ]);
    }

    private function populateResultM2tsSettings(array $json): M2tsSettings
    {
        return new M2tsSettings([
            'AudioBufferModel' => isset($json['audioBufferModel']) ? (string) $json['audioBufferModel'] : null,
            'AudioDuration' => isset($json['audioDuration']) ? (string) $json['audioDuration'] : null,
            'AudioFramesPerPes' => isset($json['audioFramesPerPes']) ? (int) $json['audioFramesPerPes'] : null,
            'AudioPids' => !isset($json['audioPids']) ? null : $this->populateResult__listOf__integerMin32Max8182($json['audioPids']),
            'AudioPtsOffsetDelta' => isset($json['audioPtsOffsetDelta']) ? (int) $json['audioPtsOffsetDelta'] : null,
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'BufferModel' => isset($json['bufferModel']) ? (string) $json['bufferModel'] : null,
            'DataPTSControl' => isset($json['dataPTSControl']) ? (string) $json['dataPTSControl'] : null,
            'DvbNitSettings' => empty($json['dvbNitSettings']) ? null : $this->populateResultDvbNitSettings($json['dvbNitSettings']),
            'DvbSdtSettings' => empty($json['dvbSdtSettings']) ? null : $this->populateResultDvbSdtSettings($json['dvbSdtSettings']),
            'DvbSubPids' => !isset($json['dvbSubPids']) ? null : $this->populateResult__listOf__integerMin32Max8182($json['dvbSubPids']),
            'DvbTdtSettings' => empty($json['dvbTdtSettings']) ? null : $this->populateResultDvbTdtSettings($json['dvbTdtSettings']),
            'DvbTeletextPid' => isset($json['dvbTeletextPid']) ? (int) $json['dvbTeletextPid'] : null,
            'EbpAudioInterval' => isset($json['ebpAudioInterval']) ? (string) $json['ebpAudioInterval'] : null,
            'EbpPlacement' => isset($json['ebpPlacement']) ? (string) $json['ebpPlacement'] : null,
            'EsRateInPes' => isset($json['esRateInPes']) ? (string) $json['esRateInPes'] : null,
            'ForceTsVideoEbpOrder' => isset($json['forceTsVideoEbpOrder']) ? (string) $json['forceTsVideoEbpOrder'] : null,
            'FragmentTime' => isset($json['fragmentTime']) ? (float) $json['fragmentTime'] : null,
            'KlvMetadata' => isset($json['klvMetadata']) ? (string) $json['klvMetadata'] : null,
            'MaxPcrInterval' => isset($json['maxPcrInterval']) ? (int) $json['maxPcrInterval'] : null,
            'MinEbpInterval' => isset($json['minEbpInterval']) ? (int) $json['minEbpInterval'] : null,
            'NielsenId3' => isset($json['nielsenId3']) ? (string) $json['nielsenId3'] : null,
            'NullPacketBitrate' => isset($json['nullPacketBitrate']) ? (float) $json['nullPacketBitrate'] : null,
            'PatInterval' => isset($json['patInterval']) ? (int) $json['patInterval'] : null,
            'PcrControl' => isset($json['pcrControl']) ? (string) $json['pcrControl'] : null,
            'PcrPid' => isset($json['pcrPid']) ? (int) $json['pcrPid'] : null,
            'PmtInterval' => isset($json['pmtInterval']) ? (int) $json['pmtInterval'] : null,
            'PmtPid' => isset($json['pmtPid']) ? (int) $json['pmtPid'] : null,
            'PreventBufferUnderflow' => isset($json['preventBufferUnderflow']) ? (string) $json['preventBufferUnderflow'] : null,
            'PrivateMetadataPid' => isset($json['privateMetadataPid']) ? (int) $json['privateMetadataPid'] : null,
            'ProgramNumber' => isset($json['programNumber']) ? (int) $json['programNumber'] : null,
            'PtsOffset' => isset($json['ptsOffset']) ? (int) $json['ptsOffset'] : null,
            'PtsOffsetMode' => isset($json['ptsOffsetMode']) ? (string) $json['ptsOffsetMode'] : null,
            'RateMode' => isset($json['rateMode']) ? (string) $json['rateMode'] : null,
            'Scte35Esam' => empty($json['scte35Esam']) ? null : $this->populateResultM2tsScte35Esam($json['scte35Esam']),
            'Scte35Pid' => isset($json['scte35Pid']) ? (int) $json['scte35Pid'] : null,
            'Scte35Source' => isset($json['scte35Source']) ? (string) $json['scte35Source'] : null,
            'SegmentationMarkers' => isset($json['segmentationMarkers']) ? (string) $json['segmentationMarkers'] : null,
            'SegmentationStyle' => isset($json['segmentationStyle']) ? (string) $json['segmentationStyle'] : null,
            'SegmentationTime' => isset($json['segmentationTime']) ? (float) $json['segmentationTime'] : null,
            'TimedMetadataPid' => isset($json['timedMetadataPid']) ? (int) $json['timedMetadataPid'] : null,
            'TransportStreamId' => isset($json['transportStreamId']) ? (int) $json['transportStreamId'] : null,
            'VideoPid' => isset($json['videoPid']) ? (int) $json['videoPid'] : null,
        ]);
    }

    private function populateResultM3u8Settings(array $json): M3u8Settings
    {
        return new M3u8Settings([
            'AudioDuration' => isset($json['audioDuration']) ? (string) $json['audioDuration'] : null,
            'AudioFramesPerPes' => isset($json['audioFramesPerPes']) ? (int) $json['audioFramesPerPes'] : null,
            'AudioPids' => !isset($json['audioPids']) ? null : $this->populateResult__listOf__integerMin32Max8182($json['audioPids']),
            'AudioPtsOffsetDelta' => isset($json['audioPtsOffsetDelta']) ? (int) $json['audioPtsOffsetDelta'] : null,
            'DataPTSControl' => isset($json['dataPTSControl']) ? (string) $json['dataPTSControl'] : null,
            'MaxPcrInterval' => isset($json['maxPcrInterval']) ? (int) $json['maxPcrInterval'] : null,
            'NielsenId3' => isset($json['nielsenId3']) ? (string) $json['nielsenId3'] : null,
            'PatInterval' => isset($json['patInterval']) ? (int) $json['patInterval'] : null,
            'PcrControl' => isset($json['pcrControl']) ? (string) $json['pcrControl'] : null,
            'PcrPid' => isset($json['pcrPid']) ? (int) $json['pcrPid'] : null,
            'PmtInterval' => isset($json['pmtInterval']) ? (int) $json['pmtInterval'] : null,
            'PmtPid' => isset($json['pmtPid']) ? (int) $json['pmtPid'] : null,
            'PrivateMetadataPid' => isset($json['privateMetadataPid']) ? (int) $json['privateMetadataPid'] : null,
            'ProgramNumber' => isset($json['programNumber']) ? (int) $json['programNumber'] : null,
            'PtsOffset' => isset($json['ptsOffset']) ? (int) $json['ptsOffset'] : null,
            'PtsOffsetMode' => isset($json['ptsOffsetMode']) ? (string) $json['ptsOffsetMode'] : null,
            'Scte35Pid' => isset($json['scte35Pid']) ? (int) $json['scte35Pid'] : null,
            'Scte35Source' => isset($json['scte35Source']) ? (string) $json['scte35Source'] : null,
            'TimedMetadata' => isset($json['timedMetadata']) ? (string) $json['timedMetadata'] : null,
            'TimedMetadataPid' => isset($json['timedMetadataPid']) ? (int) $json['timedMetadataPid'] : null,
            'TransportStreamId' => isset($json['transportStreamId']) ? (int) $json['transportStreamId'] : null,
            'VideoPid' => isset($json['videoPid']) ? (int) $json['videoPid'] : null,
        ]);
    }

    private function populateResultMinBottomRenditionSize(array $json): MinBottomRenditionSize
    {
        return new MinBottomRenditionSize([
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
        ]);
    }

    private function populateResultMinTopRenditionSize(array $json): MinTopRenditionSize
    {
        return new MinTopRenditionSize([
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
        ]);
    }

    private function populateResultMotionImageInserter(array $json): MotionImageInserter
    {
        return new MotionImageInserter([
            'Framerate' => empty($json['framerate']) ? null : $this->populateResultMotionImageInsertionFramerate($json['framerate']),
            'Input' => isset($json['input']) ? (string) $json['input'] : null,
            'InsertionMode' => isset($json['insertionMode']) ? (string) $json['insertionMode'] : null,
            'Offset' => empty($json['offset']) ? null : $this->populateResultMotionImageInsertionOffset($json['offset']),
            'Playback' => isset($json['playback']) ? (string) $json['playback'] : null,
            'StartTime' => isset($json['startTime']) ? (string) $json['startTime'] : null,
        ]);
    }

    private function populateResultMotionImageInsertionFramerate(array $json): MotionImageInsertionFramerate
    {
        return new MotionImageInsertionFramerate([
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
        ]);
    }

    private function populateResultMotionImageInsertionOffset(array $json): MotionImageInsertionOffset
    {
        return new MotionImageInsertionOffset([
            'ImageX' => isset($json['imageX']) ? (int) $json['imageX'] : null,
            'ImageY' => isset($json['imageY']) ? (int) $json['imageY'] : null,
        ]);
    }

    private function populateResultMovSettings(array $json): MovSettings
    {
        return new MovSettings([
            'ClapAtom' => isset($json['clapAtom']) ? (string) $json['clapAtom'] : null,
            'CslgAtom' => isset($json['cslgAtom']) ? (string) $json['cslgAtom'] : null,
            'Mpeg2FourCCControl' => isset($json['mpeg2FourCCControl']) ? (string) $json['mpeg2FourCCControl'] : null,
            'PaddingControl' => isset($json['paddingControl']) ? (string) $json['paddingControl'] : null,
            'Reference' => isset($json['reference']) ? (string) $json['reference'] : null,
        ]);
    }

    private function populateResultMp2Settings(array $json): Mp2Settings
    {
        return new Mp2Settings([
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'Channels' => isset($json['channels']) ? (int) $json['channels'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
        ]);
    }

    private function populateResultMp3Settings(array $json): Mp3Settings
    {
        return new Mp3Settings([
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'Channels' => isset($json['channels']) ? (int) $json['channels'] : null,
            'RateControlMode' => isset($json['rateControlMode']) ? (string) $json['rateControlMode'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
            'VbrQuality' => isset($json['vbrQuality']) ? (int) $json['vbrQuality'] : null,
        ]);
    }

    private function populateResultMp4Settings(array $json): Mp4Settings
    {
        return new Mp4Settings([
            'AudioDuration' => isset($json['audioDuration']) ? (string) $json['audioDuration'] : null,
            'C2paManifest' => isset($json['c2paManifest']) ? (string) $json['c2paManifest'] : null,
            'CertificateSecret' => isset($json['certificateSecret']) ? (string) $json['certificateSecret'] : null,
            'CslgAtom' => isset($json['cslgAtom']) ? (string) $json['cslgAtom'] : null,
            'CttsVersion' => isset($json['cttsVersion']) ? (int) $json['cttsVersion'] : null,
            'FreeSpaceBox' => isset($json['freeSpaceBox']) ? (string) $json['freeSpaceBox'] : null,
            'MoovPlacement' => isset($json['moovPlacement']) ? (string) $json['moovPlacement'] : null,
            'Mp4MajorBrand' => isset($json['mp4MajorBrand']) ? (string) $json['mp4MajorBrand'] : null,
            'SigningKmsKey' => isset($json['signingKmsKey']) ? (string) $json['signingKmsKey'] : null,
        ]);
    }

    private function populateResultMpdSettings(array $json): MpdSettings
    {
        return new MpdSettings([
            'AccessibilityCaptionHints' => isset($json['accessibilityCaptionHints']) ? (string) $json['accessibilityCaptionHints'] : null,
            'AudioDuration' => isset($json['audioDuration']) ? (string) $json['audioDuration'] : null,
            'CaptionContainerType' => isset($json['captionContainerType']) ? (string) $json['captionContainerType'] : null,
            'KlvMetadata' => isset($json['klvMetadata']) ? (string) $json['klvMetadata'] : null,
            'ManifestMetadataSignaling' => isset($json['manifestMetadataSignaling']) ? (string) $json['manifestMetadataSignaling'] : null,
            'Scte35Esam' => isset($json['scte35Esam']) ? (string) $json['scte35Esam'] : null,
            'Scte35Source' => isset($json['scte35Source']) ? (string) $json['scte35Source'] : null,
            'TimedMetadata' => isset($json['timedMetadata']) ? (string) $json['timedMetadata'] : null,
            'TimedMetadataBoxVersion' => isset($json['timedMetadataBoxVersion']) ? (string) $json['timedMetadataBoxVersion'] : null,
            'TimedMetadataSchemeIdUri' => isset($json['timedMetadataSchemeIdUri']) ? (string) $json['timedMetadataSchemeIdUri'] : null,
            'TimedMetadataValue' => isset($json['timedMetadataValue']) ? (string) $json['timedMetadataValue'] : null,
        ]);
    }

    private function populateResultMpeg2Settings(array $json): Mpeg2Settings
    {
        return new Mpeg2Settings([
            'AdaptiveQuantization' => isset($json['adaptiveQuantization']) ? (string) $json['adaptiveQuantization'] : null,
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'CodecLevel' => isset($json['codecLevel']) ? (string) $json['codecLevel'] : null,
            'CodecProfile' => isset($json['codecProfile']) ? (string) $json['codecProfile'] : null,
            'DynamicSubGop' => isset($json['dynamicSubGop']) ? (string) $json['dynamicSubGop'] : null,
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopClosedCadence' => isset($json['gopClosedCadence']) ? (int) $json['gopClosedCadence'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'GopSizeUnits' => isset($json['gopSizeUnits']) ? (string) $json['gopSizeUnits'] : null,
            'HrdBufferFinalFillPercentage' => isset($json['hrdBufferFinalFillPercentage']) ? (int) $json['hrdBufferFinalFillPercentage'] : null,
            'HrdBufferInitialFillPercentage' => isset($json['hrdBufferInitialFillPercentage']) ? (int) $json['hrdBufferInitialFillPercentage'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (string) $json['interlaceMode'] : null,
            'IntraDcPrecision' => isset($json['intraDcPrecision']) ? (string) $json['intraDcPrecision'] : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'MinIInterval' => isset($json['minIInterval']) ? (int) $json['minIInterval'] : null,
            'NumberBFramesBetweenReferenceFrames' => isset($json['numberBFramesBetweenReferenceFrames']) ? (int) $json['numberBFramesBetweenReferenceFrames'] : null,
            'ParControl' => isset($json['parControl']) ? (string) $json['parControl'] : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (string) $json['qualityTuningLevel'] : null,
            'RateControlMode' => isset($json['rateControlMode']) ? (string) $json['rateControlMode'] : null,
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (string) $json['scanTypeConversionMode'] : null,
            'SceneChangeDetect' => isset($json['sceneChangeDetect']) ? (string) $json['sceneChangeDetect'] : null,
            'SlowPal' => isset($json['slowPal']) ? (string) $json['slowPal'] : null,
            'Softness' => isset($json['softness']) ? (int) $json['softness'] : null,
            'SpatialAdaptiveQuantization' => isset($json['spatialAdaptiveQuantization']) ? (string) $json['spatialAdaptiveQuantization'] : null,
            'Syntax' => isset($json['syntax']) ? (string) $json['syntax'] : null,
            'Telecine' => isset($json['telecine']) ? (string) $json['telecine'] : null,
            'TemporalAdaptiveQuantization' => isset($json['temporalAdaptiveQuantization']) ? (string) $json['temporalAdaptiveQuantization'] : null,
        ]);
    }

    private function populateResultMsSmoothAdditionalManifest(array $json): MsSmoothAdditionalManifest
    {
        return new MsSmoothAdditionalManifest([
            'ManifestNameModifier' => isset($json['manifestNameModifier']) ? (string) $json['manifestNameModifier'] : null,
            'SelectedOutputs' => !isset($json['selectedOutputs']) ? null : $this->populateResult__listOf__stringMin1($json['selectedOutputs']),
        ]);
    }

    private function populateResultMsSmoothEncryptionSettings(array $json): MsSmoothEncryptionSettings
    {
        return new MsSmoothEncryptionSettings([
            'SpekeKeyProvider' => empty($json['spekeKeyProvider']) ? null : $this->populateResultSpekeKeyProvider($json['spekeKeyProvider']),
        ]);
    }

    private function populateResultMsSmoothGroupSettings(array $json): MsSmoothGroupSettings
    {
        return new MsSmoothGroupSettings([
            'AdditionalManifests' => !isset($json['additionalManifests']) ? null : $this->populateResult__listOfMsSmoothAdditionalManifest($json['additionalManifests']),
            'AudioDeduplication' => isset($json['audioDeduplication']) ? (string) $json['audioDeduplication'] : null,
            'Destination' => isset($json['destination']) ? (string) $json['destination'] : null,
            'DestinationSettings' => empty($json['destinationSettings']) ? null : $this->populateResultDestinationSettings($json['destinationSettings']),
            'Encryption' => empty($json['encryption']) ? null : $this->populateResultMsSmoothEncryptionSettings($json['encryption']),
            'FragmentLength' => isset($json['fragmentLength']) ? (int) $json['fragmentLength'] : null,
            'FragmentLengthControl' => isset($json['fragmentLengthControl']) ? (string) $json['fragmentLengthControl'] : null,
            'ManifestEncoding' => isset($json['manifestEncoding']) ? (string) $json['manifestEncoding'] : null,
        ]);
    }

    private function populateResultMxfSettings(array $json): MxfSettings
    {
        return new MxfSettings([
            'AfdSignaling' => isset($json['afdSignaling']) ? (string) $json['afdSignaling'] : null,
            'Profile' => isset($json['profile']) ? (string) $json['profile'] : null,
            'XavcProfileSettings' => empty($json['xavcProfileSettings']) ? null : $this->populateResultMxfXavcProfileSettings($json['xavcProfileSettings']),
        ]);
    }

    private function populateResultMxfXavcProfileSettings(array $json): MxfXavcProfileSettings
    {
        return new MxfXavcProfileSettings([
            'DurationMode' => isset($json['durationMode']) ? (string) $json['durationMode'] : null,
            'MaxAncDataSize' => isset($json['maxAncDataSize']) ? (int) $json['maxAncDataSize'] : null,
        ]);
    }

    private function populateResultNexGuardFileMarkerSettings(array $json): NexGuardFileMarkerSettings
    {
        return new NexGuardFileMarkerSettings([
            'License' => isset($json['license']) ? (string) $json['license'] : null,
            'Payload' => isset($json['payload']) ? (int) $json['payload'] : null,
            'Preset' => isset($json['preset']) ? (string) $json['preset'] : null,
            'Strength' => isset($json['strength']) ? (string) $json['strength'] : null,
        ]);
    }

    private function populateResultNielsenConfiguration(array $json): NielsenConfiguration
    {
        return new NielsenConfiguration([
            'BreakoutCode' => isset($json['breakoutCode']) ? (int) $json['breakoutCode'] : null,
            'DistributorId' => isset($json['distributorId']) ? (string) $json['distributorId'] : null,
        ]);
    }

    private function populateResultNielsenNonLinearWatermarkSettings(array $json): NielsenNonLinearWatermarkSettings
    {
        return new NielsenNonLinearWatermarkSettings([
            'ActiveWatermarkProcess' => isset($json['activeWatermarkProcess']) ? (string) $json['activeWatermarkProcess'] : null,
            'AdiFilename' => isset($json['adiFilename']) ? (string) $json['adiFilename'] : null,
            'AssetId' => isset($json['assetId']) ? (string) $json['assetId'] : null,
            'AssetName' => isset($json['assetName']) ? (string) $json['assetName'] : null,
            'CbetSourceId' => isset($json['cbetSourceId']) ? (string) $json['cbetSourceId'] : null,
            'EpisodeId' => isset($json['episodeId']) ? (string) $json['episodeId'] : null,
            'MetadataDestination' => isset($json['metadataDestination']) ? (string) $json['metadataDestination'] : null,
            'SourceId' => isset($json['sourceId']) ? (int) $json['sourceId'] : null,
            'SourceWatermarkStatus' => isset($json['sourceWatermarkStatus']) ? (string) $json['sourceWatermarkStatus'] : null,
            'TicServerUrl' => isset($json['ticServerUrl']) ? (string) $json['ticServerUrl'] : null,
            'UniqueTicPerAudioTrack' => isset($json['uniqueTicPerAudioTrack']) ? (string) $json['uniqueTicPerAudioTrack'] : null,
        ]);
    }

    private function populateResultNoiseReducer(array $json): NoiseReducer
    {
        return new NoiseReducer([
            'Filter' => isset($json['filter']) ? (string) $json['filter'] : null,
            'FilterSettings' => empty($json['filterSettings']) ? null : $this->populateResultNoiseReducerFilterSettings($json['filterSettings']),
            'SpatialFilterSettings' => empty($json['spatialFilterSettings']) ? null : $this->populateResultNoiseReducerSpatialFilterSettings($json['spatialFilterSettings']),
            'TemporalFilterSettings' => empty($json['temporalFilterSettings']) ? null : $this->populateResultNoiseReducerTemporalFilterSettings($json['temporalFilterSettings']),
        ]);
    }

    private function populateResultNoiseReducerFilterSettings(array $json): NoiseReducerFilterSettings
    {
        return new NoiseReducerFilterSettings([
            'Strength' => isset($json['strength']) ? (int) $json['strength'] : null,
        ]);
    }

    private function populateResultNoiseReducerSpatialFilterSettings(array $json): NoiseReducerSpatialFilterSettings
    {
        return new NoiseReducerSpatialFilterSettings([
            'PostFilterSharpenStrength' => isset($json['postFilterSharpenStrength']) ? (int) $json['postFilterSharpenStrength'] : null,
            'Speed' => isset($json['speed']) ? (int) $json['speed'] : null,
            'Strength' => isset($json['strength']) ? (int) $json['strength'] : null,
        ]);
    }

    private function populateResultNoiseReducerTemporalFilterSettings(array $json): NoiseReducerTemporalFilterSettings
    {
        return new NoiseReducerTemporalFilterSettings([
            'AggressiveMode' => isset($json['aggressiveMode']) ? (int) $json['aggressiveMode'] : null,
            'PostTemporalSharpening' => isset($json['postTemporalSharpening']) ? (string) $json['postTemporalSharpening'] : null,
            'PostTemporalSharpeningStrength' => isset($json['postTemporalSharpeningStrength']) ? (string) $json['postTemporalSharpeningStrength'] : null,
            'Speed' => isset($json['speed']) ? (int) $json['speed'] : null,
            'Strength' => isset($json['strength']) ? (int) $json['strength'] : null,
        ]);
    }

    private function populateResultOpusSettings(array $json): OpusSettings
    {
        return new OpusSettings([
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'Channels' => isset($json['channels']) ? (int) $json['channels'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
        ]);
    }

    private function populateResultOutput(array $json): Output
    {
        return new Output([
            'AudioDescriptions' => !isset($json['audioDescriptions']) ? null : $this->populateResult__listOfAudioDescription($json['audioDescriptions']),
            'CaptionDescriptions' => !isset($json['captionDescriptions']) ? null : $this->populateResult__listOfCaptionDescription($json['captionDescriptions']),
            'ContainerSettings' => empty($json['containerSettings']) ? null : $this->populateResultContainerSettings($json['containerSettings']),
            'Extension' => isset($json['extension']) ? (string) $json['extension'] : null,
            'NameModifier' => isset($json['nameModifier']) ? (string) $json['nameModifier'] : null,
            'OutputSettings' => empty($json['outputSettings']) ? null : $this->populateResultOutputSettings($json['outputSettings']),
            'Preset' => isset($json['preset']) ? (string) $json['preset'] : null,
            'VideoDescription' => empty($json['videoDescription']) ? null : $this->populateResultVideoDescription($json['videoDescription']),
        ]);
    }

    private function populateResultOutputChannelMapping(array $json): OutputChannelMapping
    {
        return new OutputChannelMapping([
            'InputChannels' => !isset($json['inputChannels']) ? null : $this->populateResult__listOf__integerMinNegative60Max6($json['inputChannels']),
            'InputChannelsFineTune' => !isset($json['inputChannelsFineTune']) ? null : $this->populateResult__listOf__doubleMinNegative60Max6($json['inputChannelsFineTune']),
        ]);
    }

    private function populateResultOutputDetail(array $json): OutputDetail
    {
        return new OutputDetail([
            'DurationInMs' => isset($json['durationInMs']) ? (int) $json['durationInMs'] : null,
            'VideoDetails' => empty($json['videoDetails']) ? null : $this->populateResultVideoDetail($json['videoDetails']),
        ]);
    }

    private function populateResultOutputGroup(array $json): OutputGroup
    {
        return new OutputGroup([
            'AutomatedEncodingSettings' => empty($json['automatedEncodingSettings']) ? null : $this->populateResultAutomatedEncodingSettings($json['automatedEncodingSettings']),
            'CustomName' => isset($json['customName']) ? (string) $json['customName'] : null,
            'Name' => isset($json['name']) ? (string) $json['name'] : null,
            'OutputGroupSettings' => empty($json['outputGroupSettings']) ? null : $this->populateResultOutputGroupSettings($json['outputGroupSettings']),
            'Outputs' => !isset($json['outputs']) ? null : $this->populateResult__listOfOutput($json['outputs']),
        ]);
    }

    private function populateResultOutputGroupDetail(array $json): OutputGroupDetail
    {
        return new OutputGroupDetail([
            'OutputDetails' => !isset($json['outputDetails']) ? null : $this->populateResult__listOfOutputDetail($json['outputDetails']),
        ]);
    }

    private function populateResultOutputGroupSettings(array $json): OutputGroupSettings
    {
        return new OutputGroupSettings([
            'CmafGroupSettings' => empty($json['cmafGroupSettings']) ? null : $this->populateResultCmafGroupSettings($json['cmafGroupSettings']),
            'DashIsoGroupSettings' => empty($json['dashIsoGroupSettings']) ? null : $this->populateResultDashIsoGroupSettings($json['dashIsoGroupSettings']),
            'FileGroupSettings' => empty($json['fileGroupSettings']) ? null : $this->populateResultFileGroupSettings($json['fileGroupSettings']),
            'HlsGroupSettings' => empty($json['hlsGroupSettings']) ? null : $this->populateResultHlsGroupSettings($json['hlsGroupSettings']),
            'MsSmoothGroupSettings' => empty($json['msSmoothGroupSettings']) ? null : $this->populateResultMsSmoothGroupSettings($json['msSmoothGroupSettings']),
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'Type' => isset($json['type']) ? (string) $json['type'] : null,
        ]);
    }

    private function populateResultOutputSettings(array $json): OutputSettings
    {
        return new OutputSettings([
            'HlsSettings' => empty($json['hlsSettings']) ? null : $this->populateResultHlsSettings($json['hlsSettings']),
        ]);
    }

    private function populateResultPartnerWatermarking(array $json): PartnerWatermarking
    {
        return new PartnerWatermarking([
            'NexguardFileMarkerSettings' => empty($json['nexguardFileMarkerSettings']) ? null : $this->populateResultNexGuardFileMarkerSettings($json['nexguardFileMarkerSettings']),
        ]);
    }

    private function populateResultProresSettings(array $json): ProresSettings
    {
        return new ProresSettings([
            'ChromaSampling' => isset($json['chromaSampling']) ? (string) $json['chromaSampling'] : null,
            'CodecProfile' => isset($json['codecProfile']) ? (string) $json['codecProfile'] : null,
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (string) $json['interlaceMode'] : null,
            'ParControl' => isset($json['parControl']) ? (string) $json['parControl'] : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (string) $json['scanTypeConversionMode'] : null,
            'SlowPal' => isset($json['slowPal']) ? (string) $json['slowPal'] : null,
            'Telecine' => isset($json['telecine']) ? (string) $json['telecine'] : null,
        ]);
    }

    private function populateResultQueueTransition(array $json): QueueTransition
    {
        return new QueueTransition([
            'DestinationQueue' => isset($json['destinationQueue']) ? (string) $json['destinationQueue'] : null,
            'SourceQueue' => isset($json['sourceQueue']) ? (string) $json['sourceQueue'] : null,
            'Timestamp' => isset($json['timestamp']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['timestamp']))) ? $d : null,
        ]);
    }

    private function populateResultRectangle(array $json): Rectangle
    {
        return new Rectangle([
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
            'X' => isset($json['x']) ? (int) $json['x'] : null,
            'Y' => isset($json['y']) ? (int) $json['y'] : null,
        ]);
    }

    private function populateResultRemixSettings(array $json): RemixSettings
    {
        return new RemixSettings([
            'AudioDescriptionAudioChannel' => isset($json['audioDescriptionAudioChannel']) ? (int) $json['audioDescriptionAudioChannel'] : null,
            'AudioDescriptionDataChannel' => isset($json['audioDescriptionDataChannel']) ? (int) $json['audioDescriptionDataChannel'] : null,
            'ChannelMapping' => empty($json['channelMapping']) ? null : $this->populateResultChannelMapping($json['channelMapping']),
            'ChannelsIn' => isset($json['channelsIn']) ? (int) $json['channelsIn'] : null,
            'ChannelsOut' => isset($json['channelsOut']) ? (int) $json['channelsOut'] : null,
        ]);
    }

    private function populateResultS3DestinationAccessControl(array $json): S3DestinationAccessControl
    {
        return new S3DestinationAccessControl([
            'CannedAcl' => isset($json['cannedAcl']) ? (string) $json['cannedAcl'] : null,
        ]);
    }

    private function populateResultS3DestinationSettings(array $json): S3DestinationSettings
    {
        return new S3DestinationSettings([
            'AccessControl' => empty($json['accessControl']) ? null : $this->populateResultS3DestinationAccessControl($json['accessControl']),
            'Encryption' => empty($json['encryption']) ? null : $this->populateResultS3EncryptionSettings($json['encryption']),
            'StorageClass' => isset($json['storageClass']) ? (string) $json['storageClass'] : null,
        ]);
    }

    private function populateResultS3EncryptionSettings(array $json): S3EncryptionSettings
    {
        return new S3EncryptionSettings([
            'EncryptionType' => isset($json['encryptionType']) ? (string) $json['encryptionType'] : null,
            'KmsEncryptionContext' => isset($json['kmsEncryptionContext']) ? (string) $json['kmsEncryptionContext'] : null,
            'KmsKeyArn' => isset($json['kmsKeyArn']) ? (string) $json['kmsKeyArn'] : null,
        ]);
    }

    private function populateResultSccDestinationSettings(array $json): SccDestinationSettings
    {
        return new SccDestinationSettings([
            'Framerate' => isset($json['framerate']) ? (string) $json['framerate'] : null,
        ]);
    }

    private function populateResultSpekeKeyProvider(array $json): SpekeKeyProvider
    {
        return new SpekeKeyProvider([
            'CertificateArn' => isset($json['certificateArn']) ? (string) $json['certificateArn'] : null,
            'EncryptionContractConfiguration' => empty($json['encryptionContractConfiguration']) ? null : $this->populateResultEncryptionContractConfiguration($json['encryptionContractConfiguration']),
            'ResourceId' => isset($json['resourceId']) ? (string) $json['resourceId'] : null,
            'SystemIds' => !isset($json['systemIds']) ? null : $this->populateResult__listOf__stringPattern09aFAF809aFAF409aFAF409aFAF409aFAF12($json['systemIds']),
            'Url' => isset($json['url']) ? (string) $json['url'] : null,
        ]);
    }

    private function populateResultSpekeKeyProviderCmaf(array $json): SpekeKeyProviderCmaf
    {
        return new SpekeKeyProviderCmaf([
            'CertificateArn' => isset($json['certificateArn']) ? (string) $json['certificateArn'] : null,
            'DashSignaledSystemIds' => !isset($json['dashSignaledSystemIds']) ? null : $this->populateResult__listOf__stringMin36Max36Pattern09aFAF809aFAF409aFAF409aFAF409aFAF12($json['dashSignaledSystemIds']),
            'EncryptionContractConfiguration' => empty($json['encryptionContractConfiguration']) ? null : $this->populateResultEncryptionContractConfiguration($json['encryptionContractConfiguration']),
            'HlsSignaledSystemIds' => !isset($json['hlsSignaledSystemIds']) ? null : $this->populateResult__listOf__stringMin36Max36Pattern09aFAF809aFAF409aFAF409aFAF409aFAF12($json['hlsSignaledSystemIds']),
            'ResourceId' => isset($json['resourceId']) ? (string) $json['resourceId'] : null,
            'Url' => isset($json['url']) ? (string) $json['url'] : null,
        ]);
    }

    private function populateResultSrtDestinationSettings(array $json): SrtDestinationSettings
    {
        return new SrtDestinationSettings([
            'StylePassthrough' => isset($json['stylePassthrough']) ? (string) $json['stylePassthrough'] : null,
        ]);
    }

    private function populateResultStaticKeyProvider(array $json): StaticKeyProvider
    {
        return new StaticKeyProvider([
            'KeyFormat' => isset($json['keyFormat']) ? (string) $json['keyFormat'] : null,
            'KeyFormatVersions' => isset($json['keyFormatVersions']) ? (string) $json['keyFormatVersions'] : null,
            'StaticKeyValue' => isset($json['staticKeyValue']) ? (string) $json['staticKeyValue'] : null,
            'Url' => isset($json['url']) ? (string) $json['url'] : null,
        ]);
    }

    private function populateResultTeletextDestinationSettings(array $json): TeletextDestinationSettings
    {
        return new TeletextDestinationSettings([
            'PageNumber' => isset($json['pageNumber']) ? (string) $json['pageNumber'] : null,
            'PageTypes' => !isset($json['pageTypes']) ? null : $this->populateResult__listOfTeletextPageType($json['pageTypes']),
        ]);
    }

    private function populateResultTeletextSourceSettings(array $json): TeletextSourceSettings
    {
        return new TeletextSourceSettings([
            'PageNumber' => isset($json['pageNumber']) ? (string) $json['pageNumber'] : null,
        ]);
    }

    private function populateResultTimecodeBurnin(array $json): TimecodeBurnin
    {
        return new TimecodeBurnin([
            'FontSize' => isset($json['fontSize']) ? (int) $json['fontSize'] : null,
            'Position' => isset($json['position']) ? (string) $json['position'] : null,
            'Prefix' => isset($json['prefix']) ? (string) $json['prefix'] : null,
        ]);
    }

    private function populateResultTimecodeConfig(array $json): TimecodeConfig
    {
        return new TimecodeConfig([
            'Anchor' => isset($json['anchor']) ? (string) $json['anchor'] : null,
            'Source' => isset($json['source']) ? (string) $json['source'] : null,
            'Start' => isset($json['start']) ? (string) $json['start'] : null,
            'TimestampOffset' => isset($json['timestampOffset']) ? (string) $json['timestampOffset'] : null,
        ]);
    }

    private function populateResultTimedMetadataInsertion(array $json): TimedMetadataInsertion
    {
        return new TimedMetadataInsertion([
            'Id3Insertions' => !isset($json['id3Insertions']) ? null : $this->populateResult__listOfId3Insertion($json['id3Insertions']),
        ]);
    }

    private function populateResultTiming(array $json): Timing
    {
        return new Timing([
            'FinishTime' => isset($json['finishTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['finishTime']))) ? $d : null,
            'StartTime' => isset($json['startTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['startTime']))) ? $d : null,
            'SubmitTime' => isset($json['submitTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['submitTime']))) ? $d : null,
        ]);
    }

    private function populateResultTrackSourceSettings(array $json): TrackSourceSettings
    {
        return new TrackSourceSettings([
            'TrackNumber' => isset($json['trackNumber']) ? (int) $json['trackNumber'] : null,
        ]);
    }

    private function populateResultTtmlDestinationSettings(array $json): TtmlDestinationSettings
    {
        return new TtmlDestinationSettings([
            'StylePassthrough' => isset($json['stylePassthrough']) ? (string) $json['stylePassthrough'] : null,
        ]);
    }

    private function populateResultUncompressedSettings(array $json): UncompressedSettings
    {
        return new UncompressedSettings([
            'Fourcc' => isset($json['fourcc']) ? (string) $json['fourcc'] : null,
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (string) $json['interlaceMode'] : null,
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (string) $json['scanTypeConversionMode'] : null,
            'SlowPal' => isset($json['slowPal']) ? (string) $json['slowPal'] : null,
            'Telecine' => isset($json['telecine']) ? (string) $json['telecine'] : null,
        ]);
    }

    private function populateResultVc3Settings(array $json): Vc3Settings
    {
        return new Vc3Settings([
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (string) $json['interlaceMode'] : null,
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (string) $json['scanTypeConversionMode'] : null,
            'SlowPal' => isset($json['slowPal']) ? (string) $json['slowPal'] : null,
            'Telecine' => isset($json['telecine']) ? (string) $json['telecine'] : null,
            'Vc3Class' => isset($json['vc3Class']) ? (string) $json['vc3Class'] : null,
        ]);
    }

    private function populateResultVideoCodecSettings(array $json): VideoCodecSettings
    {
        return new VideoCodecSettings([
            'Av1Settings' => empty($json['av1Settings']) ? null : $this->populateResultAv1Settings($json['av1Settings']),
            'AvcIntraSettings' => empty($json['avcIntraSettings']) ? null : $this->populateResultAvcIntraSettings($json['avcIntraSettings']),
            'Codec' => isset($json['codec']) ? (string) $json['codec'] : null,
            'FrameCaptureSettings' => empty($json['frameCaptureSettings']) ? null : $this->populateResultFrameCaptureSettings($json['frameCaptureSettings']),
            'GifSettings' => empty($json['gifSettings']) ? null : $this->populateResultGifSettings($json['gifSettings']),
            'H264Settings' => empty($json['h264Settings']) ? null : $this->populateResultH264Settings($json['h264Settings']),
            'H265Settings' => empty($json['h265Settings']) ? null : $this->populateResultH265Settings($json['h265Settings']),
            'Mpeg2Settings' => empty($json['mpeg2Settings']) ? null : $this->populateResultMpeg2Settings($json['mpeg2Settings']),
            'ProresSettings' => empty($json['proresSettings']) ? null : $this->populateResultProresSettings($json['proresSettings']),
            'UncompressedSettings' => empty($json['uncompressedSettings']) ? null : $this->populateResultUncompressedSettings($json['uncompressedSettings']),
            'Vc3Settings' => empty($json['vc3Settings']) ? null : $this->populateResultVc3Settings($json['vc3Settings']),
            'Vp8Settings' => empty($json['vp8Settings']) ? null : $this->populateResultVp8Settings($json['vp8Settings']),
            'Vp9Settings' => empty($json['vp9Settings']) ? null : $this->populateResultVp9Settings($json['vp9Settings']),
            'XavcSettings' => empty($json['xavcSettings']) ? null : $this->populateResultXavcSettings($json['xavcSettings']),
        ]);
    }

    private function populateResultVideoDescription(array $json): VideoDescription
    {
        return new VideoDescription([
            'AfdSignaling' => isset($json['afdSignaling']) ? (string) $json['afdSignaling'] : null,
            'AntiAlias' => isset($json['antiAlias']) ? (string) $json['antiAlias'] : null,
            'ChromaPositionMode' => isset($json['chromaPositionMode']) ? (string) $json['chromaPositionMode'] : null,
            'CodecSettings' => empty($json['codecSettings']) ? null : $this->populateResultVideoCodecSettings($json['codecSettings']),
            'ColorMetadata' => isset($json['colorMetadata']) ? (string) $json['colorMetadata'] : null,
            'Crop' => empty($json['crop']) ? null : $this->populateResultRectangle($json['crop']),
            'DropFrameTimecode' => isset($json['dropFrameTimecode']) ? (string) $json['dropFrameTimecode'] : null,
            'FixedAfd' => isset($json['fixedAfd']) ? (int) $json['fixedAfd'] : null,
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'Position' => empty($json['position']) ? null : $this->populateResultRectangle($json['position']),
            'RespondToAfd' => isset($json['respondToAfd']) ? (string) $json['respondToAfd'] : null,
            'ScalingBehavior' => isset($json['scalingBehavior']) ? (string) $json['scalingBehavior'] : null,
            'Sharpness' => isset($json['sharpness']) ? (int) $json['sharpness'] : null,
            'TimecodeInsertion' => isset($json['timecodeInsertion']) ? (string) $json['timecodeInsertion'] : null,
            'TimecodeTrack' => isset($json['timecodeTrack']) ? (string) $json['timecodeTrack'] : null,
            'VideoPreprocessors' => empty($json['videoPreprocessors']) ? null : $this->populateResultVideoPreprocessor($json['videoPreprocessors']),
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
        ]);
    }

    private function populateResultVideoDetail(array $json): VideoDetail
    {
        return new VideoDetail([
            'HeightInPx' => isset($json['heightInPx']) ? (int) $json['heightInPx'] : null,
            'WidthInPx' => isset($json['widthInPx']) ? (int) $json['widthInPx'] : null,
        ]);
    }

    private function populateResultVideoOverlay(array $json): VideoOverlay
    {
        return new VideoOverlay([
            'Crop' => empty($json['crop']) ? null : $this->populateResultVideoOverlayCrop($json['crop']),
            'EndTimecode' => isset($json['endTimecode']) ? (string) $json['endTimecode'] : null,
            'InitialPosition' => empty($json['initialPosition']) ? null : $this->populateResultVideoOverlayPosition($json['initialPosition']),
            'Input' => empty($json['input']) ? null : $this->populateResultVideoOverlayInput($json['input']),
            'Playback' => isset($json['playback']) ? (string) $json['playback'] : null,
            'StartTimecode' => isset($json['startTimecode']) ? (string) $json['startTimecode'] : null,
            'Transitions' => !isset($json['transitions']) ? null : $this->populateResult__listOfVideoOverlayTransition($json['transitions']),
        ]);
    }

    private function populateResultVideoOverlayCrop(array $json): VideoOverlayCrop
    {
        return new VideoOverlayCrop([
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'Unit' => isset($json['unit']) ? (string) $json['unit'] : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
            'X' => isset($json['x']) ? (int) $json['x'] : null,
            'Y' => isset($json['y']) ? (int) $json['y'] : null,
        ]);
    }

    private function populateResultVideoOverlayInput(array $json): VideoOverlayInput
    {
        return new VideoOverlayInput([
            'FileInput' => isset($json['fileInput']) ? (string) $json['fileInput'] : null,
            'InputClippings' => !isset($json['inputClippings']) ? null : $this->populateResult__listOfVideoOverlayInputClipping($json['inputClippings']),
            'TimecodeSource' => isset($json['timecodeSource']) ? (string) $json['timecodeSource'] : null,
            'TimecodeStart' => isset($json['timecodeStart']) ? (string) $json['timecodeStart'] : null,
        ]);
    }

    private function populateResultVideoOverlayInputClipping(array $json): VideoOverlayInputClipping
    {
        return new VideoOverlayInputClipping([
            'EndTimecode' => isset($json['endTimecode']) ? (string) $json['endTimecode'] : null,
            'StartTimecode' => isset($json['startTimecode']) ? (string) $json['startTimecode'] : null,
        ]);
    }

    private function populateResultVideoOverlayPosition(array $json): VideoOverlayPosition
    {
        return new VideoOverlayPosition([
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'Unit' => isset($json['unit']) ? (string) $json['unit'] : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
            'XPosition' => isset($json['xPosition']) ? (int) $json['xPosition'] : null,
            'YPosition' => isset($json['yPosition']) ? (int) $json['yPosition'] : null,
        ]);
    }

    private function populateResultVideoOverlayTransition(array $json): VideoOverlayTransition
    {
        return new VideoOverlayTransition([
            'EndPosition' => empty($json['endPosition']) ? null : $this->populateResultVideoOverlayPosition($json['endPosition']),
            'EndTimecode' => isset($json['endTimecode']) ? (string) $json['endTimecode'] : null,
            'StartTimecode' => isset($json['startTimecode']) ? (string) $json['startTimecode'] : null,
        ]);
    }

    private function populateResultVideoPreprocessor(array $json): VideoPreprocessor
    {
        return new VideoPreprocessor([
            'ColorCorrector' => empty($json['colorCorrector']) ? null : $this->populateResultColorCorrector($json['colorCorrector']),
            'Deinterlacer' => empty($json['deinterlacer']) ? null : $this->populateResultDeinterlacer($json['deinterlacer']),
            'DolbyVision' => empty($json['dolbyVision']) ? null : $this->populateResultDolbyVision($json['dolbyVision']),
            'Hdr10Plus' => empty($json['hdr10Plus']) ? null : $this->populateResultHdr10Plus($json['hdr10Plus']),
            'ImageInserter' => empty($json['imageInserter']) ? null : $this->populateResultImageInserter($json['imageInserter']),
            'NoiseReducer' => empty($json['noiseReducer']) ? null : $this->populateResultNoiseReducer($json['noiseReducer']),
            'PartnerWatermarking' => empty($json['partnerWatermarking']) ? null : $this->populateResultPartnerWatermarking($json['partnerWatermarking']),
            'TimecodeBurnin' => empty($json['timecodeBurnin']) ? null : $this->populateResultTimecodeBurnin($json['timecodeBurnin']),
        ]);
    }

    private function populateResultVideoSelector(array $json): VideoSelector
    {
        return new VideoSelector([
            'AlphaBehavior' => isset($json['alphaBehavior']) ? (string) $json['alphaBehavior'] : null,
            'ColorSpace' => isset($json['colorSpace']) ? (string) $json['colorSpace'] : null,
            'ColorSpaceUsage' => isset($json['colorSpaceUsage']) ? (string) $json['colorSpaceUsage'] : null,
            'EmbeddedTimecodeOverride' => isset($json['embeddedTimecodeOverride']) ? (string) $json['embeddedTimecodeOverride'] : null,
            'Hdr10Metadata' => empty($json['hdr10Metadata']) ? null : $this->populateResultHdr10Metadata($json['hdr10Metadata']),
            'MaxLuminance' => isset($json['maxLuminance']) ? (int) $json['maxLuminance'] : null,
            'PadVideo' => isset($json['padVideo']) ? (string) $json['padVideo'] : null,
            'Pid' => isset($json['pid']) ? (int) $json['pid'] : null,
            'ProgramNumber' => isset($json['programNumber']) ? (int) $json['programNumber'] : null,
            'Rotate' => isset($json['rotate']) ? (string) $json['rotate'] : null,
            'SampleRange' => isset($json['sampleRange']) ? (string) $json['sampleRange'] : null,
        ]);
    }

    private function populateResultVorbisSettings(array $json): VorbisSettings
    {
        return new VorbisSettings([
            'Channels' => isset($json['channels']) ? (int) $json['channels'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
            'VbrQuality' => isset($json['vbrQuality']) ? (int) $json['vbrQuality'] : null,
        ]);
    }

    private function populateResultVp8Settings(array $json): Vp8Settings
    {
        return new Vp8Settings([
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'ParControl' => isset($json['parControl']) ? (string) $json['parControl'] : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (string) $json['qualityTuningLevel'] : null,
            'RateControlMode' => isset($json['rateControlMode']) ? (string) $json['rateControlMode'] : null,
        ]);
    }

    private function populateResultVp9Settings(array $json): Vp9Settings
    {
        return new Vp9Settings([
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'ParControl' => isset($json['parControl']) ? (string) $json['parControl'] : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (string) $json['qualityTuningLevel'] : null,
            'RateControlMode' => isset($json['rateControlMode']) ? (string) $json['rateControlMode'] : null,
        ]);
    }

    private function populateResultWarningGroup(array $json): WarningGroup
    {
        return new WarningGroup([
            'Code' => (int) $json['code'],
            'Count' => (int) $json['count'],
        ]);
    }

    private function populateResultWavSettings(array $json): WavSettings
    {
        return new WavSettings([
            'BitDepth' => isset($json['bitDepth']) ? (int) $json['bitDepth'] : null,
            'Channels' => isset($json['channels']) ? (int) $json['channels'] : null,
            'Format' => isset($json['format']) ? (string) $json['format'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
        ]);
    }

    private function populateResultWebvttDestinationSettings(array $json): WebvttDestinationSettings
    {
        return new WebvttDestinationSettings([
            'Accessibility' => isset($json['accessibility']) ? (string) $json['accessibility'] : null,
            'StylePassthrough' => isset($json['stylePassthrough']) ? (string) $json['stylePassthrough'] : null,
        ]);
    }

    private function populateResultWebvttHlsSourceSettings(array $json): WebvttHlsSourceSettings
    {
        return new WebvttHlsSourceSettings([
            'RenditionGroupId' => isset($json['renditionGroupId']) ? (string) $json['renditionGroupId'] : null,
            'RenditionLanguageCode' => isset($json['renditionLanguageCode']) ? (string) $json['renditionLanguageCode'] : null,
            'RenditionName' => isset($json['renditionName']) ? (string) $json['renditionName'] : null,
        ]);
    }

    private function populateResultXavc4kIntraCbgProfileSettings(array $json): Xavc4kIntraCbgProfileSettings
    {
        return new Xavc4kIntraCbgProfileSettings([
            'XavcClass' => isset($json['xavcClass']) ? (string) $json['xavcClass'] : null,
        ]);
    }

    private function populateResultXavc4kIntraVbrProfileSettings(array $json): Xavc4kIntraVbrProfileSettings
    {
        return new Xavc4kIntraVbrProfileSettings([
            'XavcClass' => isset($json['xavcClass']) ? (string) $json['xavcClass'] : null,
        ]);
    }

    private function populateResultXavc4kProfileSettings(array $json): Xavc4kProfileSettings
    {
        return new Xavc4kProfileSettings([
            'BitrateClass' => isset($json['bitrateClass']) ? (string) $json['bitrateClass'] : null,
            'CodecProfile' => isset($json['codecProfile']) ? (string) $json['codecProfile'] : null,
            'FlickerAdaptiveQuantization' => isset($json['flickerAdaptiveQuantization']) ? (string) $json['flickerAdaptiveQuantization'] : null,
            'GopBReference' => isset($json['gopBReference']) ? (string) $json['gopBReference'] : null,
            'GopClosedCadence' => isset($json['gopClosedCadence']) ? (int) $json['gopClosedCadence'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (string) $json['qualityTuningLevel'] : null,
            'Slices' => isset($json['slices']) ? (int) $json['slices'] : null,
        ]);
    }

    private function populateResultXavcHdIntraCbgProfileSettings(array $json): XavcHdIntraCbgProfileSettings
    {
        return new XavcHdIntraCbgProfileSettings([
            'XavcClass' => isset($json['xavcClass']) ? (string) $json['xavcClass'] : null,
        ]);
    }

    private function populateResultXavcHdProfileSettings(array $json): XavcHdProfileSettings
    {
        return new XavcHdProfileSettings([
            'BitrateClass' => isset($json['bitrateClass']) ? (string) $json['bitrateClass'] : null,
            'FlickerAdaptiveQuantization' => isset($json['flickerAdaptiveQuantization']) ? (string) $json['flickerAdaptiveQuantization'] : null,
            'GopBReference' => isset($json['gopBReference']) ? (string) $json['gopBReference'] : null,
            'GopClosedCadence' => isset($json['gopClosedCadence']) ? (int) $json['gopClosedCadence'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (string) $json['interlaceMode'] : null,
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (string) $json['qualityTuningLevel'] : null,
            'Slices' => isset($json['slices']) ? (int) $json['slices'] : null,
            'Telecine' => isset($json['telecine']) ? (string) $json['telecine'] : null,
        ]);
    }

    private function populateResultXavcSettings(array $json): XavcSettings
    {
        return new XavcSettings([
            'AdaptiveQuantization' => isset($json['adaptiveQuantization']) ? (string) $json['adaptiveQuantization'] : null,
            'EntropyEncoding' => isset($json['entropyEncoding']) ? (string) $json['entropyEncoding'] : null,
            'FramerateControl' => isset($json['framerateControl']) ? (string) $json['framerateControl'] : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (string) $json['framerateConversionAlgorithm'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'Profile' => isset($json['profile']) ? (string) $json['profile'] : null,
            'SlowPal' => isset($json['slowPal']) ? (string) $json['slowPal'] : null,
            'Softness' => isset($json['softness']) ? (int) $json['softness'] : null,
            'SpatialAdaptiveQuantization' => isset($json['spatialAdaptiveQuantization']) ? (string) $json['spatialAdaptiveQuantization'] : null,
            'TemporalAdaptiveQuantization' => isset($json['temporalAdaptiveQuantization']) ? (string) $json['temporalAdaptiveQuantization'] : null,
            'Xavc4kIntraCbgProfileSettings' => empty($json['xavc4kIntraCbgProfileSettings']) ? null : $this->populateResultXavc4kIntraCbgProfileSettings($json['xavc4kIntraCbgProfileSettings']),
            'Xavc4kIntraVbrProfileSettings' => empty($json['xavc4kIntraVbrProfileSettings']) ? null : $this->populateResultXavc4kIntraVbrProfileSettings($json['xavc4kIntraVbrProfileSettings']),
            'Xavc4kProfileSettings' => empty($json['xavc4kProfileSettings']) ? null : $this->populateResultXavc4kProfileSettings($json['xavc4kProfileSettings']),
            'XavcHdIntraCbgProfileSettings' => empty($json['xavcHdIntraCbgProfileSettings']) ? null : $this->populateResultXavcHdIntraCbgProfileSettings($json['xavcHdIntraCbgProfileSettings']),
            'XavcHdProfileSettings' => empty($json['xavcHdProfileSettings']) ? null : $this->populateResultXavcHdProfileSettings($json['xavcHdProfileSettings']),
        ]);
    }

    /**
     * @return AllowedRenditionSize[]
     */
    private function populateResult__listOfAllowedRenditionSize(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAllowedRenditionSize($item);
        }

        return $items;
    }

    /**
     * @return list<AudioChannelTag::*>
     */
    private function populateResult__listOfAudioChannelTag(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return AudioDescription[]
     */
    private function populateResult__listOfAudioDescription(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAudioDescription($item);
        }

        return $items;
    }

    /**
     * @return AutomatedAbrRule[]
     */
    private function populateResult__listOfAutomatedAbrRule(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAutomatedAbrRule($item);
        }

        return $items;
    }

    /**
     * @return CaptionDescription[]
     */
    private function populateResult__listOfCaptionDescription(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultCaptionDescription($item);
        }

        return $items;
    }

    /**
     * @return CmafAdditionalManifest[]
     */
    private function populateResult__listOfCmafAdditionalManifest(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultCmafAdditionalManifest($item);
        }

        return $items;
    }

    /**
     * @return ColorConversion3DLUTSetting[]
     */
    private function populateResult__listOfColorConversion3DLUTSetting(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultColorConversion3DLUTSetting($item);
        }

        return $items;
    }

    /**
     * @return DashAdditionalManifest[]
     */
    private function populateResult__listOfDashAdditionalManifest(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultDashAdditionalManifest($item);
        }

        return $items;
    }

    /**
     * @return ForceIncludeRenditionSize[]
     */
    private function populateResult__listOfForceIncludeRenditionSize(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultForceIncludeRenditionSize($item);
        }

        return $items;
    }

    /**
     * @return list<FrameMetricType::*>
     */
    private function populateResult__listOfFrameMetricType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return list<HlsAdMarkers::*>
     */
    private function populateResult__listOfHlsAdMarkers(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return HlsAdditionalManifest[]
     */
    private function populateResult__listOfHlsAdditionalManifest(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultHlsAdditionalManifest($item);
        }

        return $items;
    }

    /**
     * @return HlsCaptionLanguageMapping[]
     */
    private function populateResult__listOfHlsCaptionLanguageMapping(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultHlsCaptionLanguageMapping($item);
        }

        return $items;
    }

    /**
     * @return HopDestination[]
     */
    private function populateResult__listOfHopDestination(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultHopDestination($item);
        }

        return $items;
    }

    /**
     * @return Id3Insertion[]
     */
    private function populateResult__listOfId3Insertion(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultId3Insertion($item);
        }

        return $items;
    }

    /**
     * @return Input[]
     */
    private function populateResult__listOfInput(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultInput($item);
        }

        return $items;
    }

    /**
     * @return InputClipping[]
     */
    private function populateResult__listOfInputClipping(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultInputClipping($item);
        }

        return $items;
    }

    /**
     * @return InsertableImage[]
     */
    private function populateResult__listOfInsertableImage(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultInsertableImage($item);
        }

        return $items;
    }

    /**
     * @return MsSmoothAdditionalManifest[]
     */
    private function populateResult__listOfMsSmoothAdditionalManifest(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultMsSmoothAdditionalManifest($item);
        }

        return $items;
    }

    /**
     * @return Output[]
     */
    private function populateResult__listOfOutput(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultOutput($item);
        }

        return $items;
    }

    /**
     * @return OutputChannelMapping[]
     */
    private function populateResult__listOfOutputChannelMapping(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultOutputChannelMapping($item);
        }

        return $items;
    }

    /**
     * @return OutputDetail[]
     */
    private function populateResult__listOfOutputDetail(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultOutputDetail($item);
        }

        return $items;
    }

    /**
     * @return OutputGroup[]
     */
    private function populateResult__listOfOutputGroup(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultOutputGroup($item);
        }

        return $items;
    }

    /**
     * @return OutputGroupDetail[]
     */
    private function populateResult__listOfOutputGroupDetail(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultOutputGroupDetail($item);
        }

        return $items;
    }

    /**
     * @return QueueTransition[]
     */
    private function populateResult__listOfQueueTransition(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultQueueTransition($item);
        }

        return $items;
    }

    /**
     * @return list<TeletextPageType::*>
     */
    private function populateResult__listOfTeletextPageType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return VideoOverlay[]
     */
    private function populateResult__listOfVideoOverlay(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultVideoOverlay($item);
        }

        return $items;
    }

    /**
     * @return VideoOverlayInputClipping[]
     */
    private function populateResult__listOfVideoOverlayInputClipping(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultVideoOverlayInputClipping($item);
        }

        return $items;
    }

    /**
     * @return VideoOverlayTransition[]
     */
    private function populateResult__listOfVideoOverlayTransition(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultVideoOverlayTransition($item);
        }

        return $items;
    }

    /**
     * @return WarningGroup[]
     */
    private function populateResult__listOfWarningGroup(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultWarningGroup($item);
        }

        return $items;
    }

    /**
     * @return float[]
     */
    private function populateResult__listOf__doubleMinNegative60Max6(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (float) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return int[]
     */
    private function populateResult__listOf__integerMin1Max2147483647(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (int) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return int[]
     */
    private function populateResult__listOf__integerMin32Max8182(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (int) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return int[]
     */
    private function populateResult__listOf__integerMinNegative60Max6(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (int) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResult__listOf__string(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResult__listOf__stringMin1(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResult__listOf__stringMin36Max36Pattern09aFAF809aFAF409aFAF409aFAF409aFAF12(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResult__listOf__stringPattern09aFAF809aFAF409aFAF409aFAF409aFAF12(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResult__listOf__stringPatternS3ASSETMAPXml(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return array<string, AudioSelector>
     */
    private function populateResult__mapOfAudioSelector(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultAudioSelector($value);
        }

        return $items;
    }

    /**
     * @return array<string, AudioSelectorGroup>
     */
    private function populateResult__mapOfAudioSelectorGroup(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultAudioSelectorGroup($value);
        }

        return $items;
    }

    /**
     * @return array<string, CaptionSelector>
     */
    private function populateResult__mapOfCaptionSelector(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultCaptionSelector($value);
        }

        return $items;
    }

    /**
     * @return array<string, DynamicAudioSelector>
     */
    private function populateResult__mapOfDynamicAudioSelector(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultDynamicAudioSelector($value);
        }

        return $items;
    }

    /**
     * @return array<string, string>
     */
    private function populateResult__mapOf__string(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }
}
