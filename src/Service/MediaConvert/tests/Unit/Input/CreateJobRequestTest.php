<?php

namespace AsyncAws\MediaConvert\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Enum\AacAudioDescriptionBroadcasterMix;
use AsyncAws\MediaConvert\Enum\AacCodecProfile;
use AsyncAws\MediaConvert\Enum\AacCodingMode;
use AsyncAws\MediaConvert\Enum\AacRateControlMode;
use AsyncAws\MediaConvert\Enum\AacRawFormat;
use AsyncAws\MediaConvert\Enum\AacSpecification;
use AsyncAws\MediaConvert\Enum\AacVbrQuality;
use AsyncAws\MediaConvert\Enum\Ac3BitstreamMode;
use AsyncAws\MediaConvert\Enum\Ac3CodingMode;
use AsyncAws\MediaConvert\Enum\Ac3DynamicRangeCompressionLine;
use AsyncAws\MediaConvert\Enum\Ac3DynamicRangeCompressionProfile;
use AsyncAws\MediaConvert\Enum\Ac3DynamicRangeCompressionRf;
use AsyncAws\MediaConvert\Enum\Ac3LfeFilter;
use AsyncAws\MediaConvert\Enum\Ac3MetadataControl;
use AsyncAws\MediaConvert\Enum\AccelerationMode;
use AsyncAws\MediaConvert\Enum\AdvancedInputFilter;
use AsyncAws\MediaConvert\Enum\AdvancedInputFilterAddTexture;
use AsyncAws\MediaConvert\Enum\AdvancedInputFilterSharpen;
use AsyncAws\MediaConvert\Enum\AfdSignaling;
use AsyncAws\MediaConvert\Enum\AlphaBehavior;
use AsyncAws\MediaConvert\Enum\AncillaryConvert608To708;
use AsyncAws\MediaConvert\Enum\AncillaryTerminateCaptions;
use AsyncAws\MediaConvert\Enum\AntiAlias;
use AsyncAws\MediaConvert\Enum\AudioChannelTag;
use AsyncAws\MediaConvert\Enum\AudioCodec;
use AsyncAws\MediaConvert\Enum\AudioDefaultSelection;
use AsyncAws\MediaConvert\Enum\AudioDurationCorrection;
use AsyncAws\MediaConvert\Enum\AudioLanguageCodeControl;
use AsyncAws\MediaConvert\Enum\AudioNormalizationAlgorithm;
use AsyncAws\MediaConvert\Enum\AudioNormalizationAlgorithmControl;
use AsyncAws\MediaConvert\Enum\AudioNormalizationLoudnessLogging;
use AsyncAws\MediaConvert\Enum\AudioNormalizationPeakCalculation;
use AsyncAws\MediaConvert\Enum\AudioSelectorType;
use AsyncAws\MediaConvert\Enum\AudioTypeControl;
use AsyncAws\MediaConvert\Enum\Av1AdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\Av1BitDepth;
use AsyncAws\MediaConvert\Enum\Av1FramerateControl;
use AsyncAws\MediaConvert\Enum\Av1FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\Av1RateControlMode;
use AsyncAws\MediaConvert\Enum\Av1SpatialAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\AvcIntraClass;
use AsyncAws\MediaConvert\Enum\AvcIntraFramerateControl;
use AsyncAws\MediaConvert\Enum\AvcIntraFramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\AvcIntraInterlaceMode;
use AsyncAws\MediaConvert\Enum\AvcIntraScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\AvcIntraSlowPal;
use AsyncAws\MediaConvert\Enum\AvcIntraTelecine;
use AsyncAws\MediaConvert\Enum\AvcIntraUhdQualityTuningLevel;
use AsyncAws\MediaConvert\Enum\BandwidthReductionFilterSharpening;
use AsyncAws\MediaConvert\Enum\BandwidthReductionFilterStrength;
use AsyncAws\MediaConvert\Enum\BillingTagsSource;
use AsyncAws\MediaConvert\Enum\BurninSubtitleAlignment;
use AsyncAws\MediaConvert\Enum\BurninSubtitleApplyFontColor;
use AsyncAws\MediaConvert\Enum\BurninSubtitleBackgroundColor;
use AsyncAws\MediaConvert\Enum\BurninSubtitleFallbackFont;
use AsyncAws\MediaConvert\Enum\BurninSubtitleFontColor;
use AsyncAws\MediaConvert\Enum\BurninSubtitleOutlineColor;
use AsyncAws\MediaConvert\Enum\BurninSubtitleShadowColor;
use AsyncAws\MediaConvert\Enum\BurnInSubtitleStylePassthrough;
use AsyncAws\MediaConvert\Enum\BurninSubtitleTeletextSpacing;
use AsyncAws\MediaConvert\Enum\CaptionDestinationType;
use AsyncAws\MediaConvert\Enum\CaptionSourceConvertPaintOnToPopOn;
use AsyncAws\MediaConvert\Enum\CaptionSourceType;
use AsyncAws\MediaConvert\Enum\CmafClientCache;
use AsyncAws\MediaConvert\Enum\CmafCodecSpecification;
use AsyncAws\MediaConvert\Enum\CmafEncryptionType;
use AsyncAws\MediaConvert\Enum\CmafImageBasedTrickPlay;
use AsyncAws\MediaConvert\Enum\CmafInitializationVectorInManifest;
use AsyncAws\MediaConvert\Enum\CmafIntervalCadence;
use AsyncAws\MediaConvert\Enum\CmafKeyProviderType;
use AsyncAws\MediaConvert\Enum\CmafManifestCompression;
use AsyncAws\MediaConvert\Enum\CmafManifestDurationFormat;
use AsyncAws\MediaConvert\Enum\CmafMpdManifestBandwidthType;
use AsyncAws\MediaConvert\Enum\CmafMpdProfile;
use AsyncAws\MediaConvert\Enum\CmafPtsOffsetHandlingForBFrames;
use AsyncAws\MediaConvert\Enum\CmafSegmentControl;
use AsyncAws\MediaConvert\Enum\CmafSegmentLengthControl;
use AsyncAws\MediaConvert\Enum\CmafStreamInfResolution;
use AsyncAws\MediaConvert\Enum\CmafTargetDurationCompatibilityMode;
use AsyncAws\MediaConvert\Enum\CmafVideoCompositionOffsets;
use AsyncAws\MediaConvert\Enum\CmafWriteDASHManifest;
use AsyncAws\MediaConvert\Enum\CmafWriteHLSManifest;
use AsyncAws\MediaConvert\Enum\CmafWriteSegmentTimelineInRepresentation;
use AsyncAws\MediaConvert\Enum\CmfcAudioDuration;
use AsyncAws\MediaConvert\Enum\CmfcAudioTrackType;
use AsyncAws\MediaConvert\Enum\CmfcDescriptiveVideoServiceFlag;
use AsyncAws\MediaConvert\Enum\CmfcIFrameOnlyManifest;
use AsyncAws\MediaConvert\Enum\CmfcKlvMetadata;
use AsyncAws\MediaConvert\Enum\CmfcManifestMetadataSignaling;
use AsyncAws\MediaConvert\Enum\CmfcScte35Esam;
use AsyncAws\MediaConvert\Enum\CmfcScte35Source;
use AsyncAws\MediaConvert\Enum\CmfcTimedMetadata;
use AsyncAws\MediaConvert\Enum\CmfcTimedMetadataBoxVersion;
use AsyncAws\MediaConvert\Enum\ColorMetadata;
use AsyncAws\MediaConvert\Enum\ColorSpace;
use AsyncAws\MediaConvert\Enum\ColorSpaceConversion;
use AsyncAws\MediaConvert\Enum\ColorSpaceUsage;
use AsyncAws\MediaConvert\Enum\ContainerType;
use AsyncAws\MediaConvert\Enum\CopyProtectionAction;
use AsyncAws\MediaConvert\Enum\DashIsoGroupAudioChannelConfigSchemeIdUri;
use AsyncAws\MediaConvert\Enum\DashIsoHbbtvCompliance;
use AsyncAws\MediaConvert\Enum\DashIsoImageBasedTrickPlay;
use AsyncAws\MediaConvert\Enum\DashIsoIntervalCadence;
use AsyncAws\MediaConvert\Enum\DashIsoMpdManifestBandwidthType;
use AsyncAws\MediaConvert\Enum\DashIsoMpdProfile;
use AsyncAws\MediaConvert\Enum\DashIsoPlaybackDeviceCompatibility;
use AsyncAws\MediaConvert\Enum\DashIsoPtsOffsetHandlingForBFrames;
use AsyncAws\MediaConvert\Enum\DashIsoSegmentControl;
use AsyncAws\MediaConvert\Enum\DashIsoSegmentLengthControl;
use AsyncAws\MediaConvert\Enum\DashIsoVideoCompositionOffsets;
use AsyncAws\MediaConvert\Enum\DashIsoWriteSegmentTimelineInRepresentation;
use AsyncAws\MediaConvert\Enum\DashManifestStyle;
use AsyncAws\MediaConvert\Enum\DecryptionMode;
use AsyncAws\MediaConvert\Enum\DeinterlaceAlgorithm;
use AsyncAws\MediaConvert\Enum\DeinterlacerControl;
use AsyncAws\MediaConvert\Enum\DeinterlacerMode;
use AsyncAws\MediaConvert\Enum\DolbyVisionLevel6Mode;
use AsyncAws\MediaConvert\Enum\DolbyVisionMapping;
use AsyncAws\MediaConvert\Enum\DolbyVisionProfile;
use AsyncAws\MediaConvert\Enum\DropFrameTimecode;
use AsyncAws\MediaConvert\Enum\DvbddsHandling;
use AsyncAws\MediaConvert\Enum\DvbSubSubtitleFallbackFont;
use AsyncAws\MediaConvert\Enum\DvbSubtitleAlignment;
use AsyncAws\MediaConvert\Enum\DvbSubtitleApplyFontColor;
use AsyncAws\MediaConvert\Enum\DvbSubtitleBackgroundColor;
use AsyncAws\MediaConvert\Enum\DvbSubtitleFontColor;
use AsyncAws\MediaConvert\Enum\DvbSubtitleOutlineColor;
use AsyncAws\MediaConvert\Enum\DvbSubtitleShadowColor;
use AsyncAws\MediaConvert\Enum\DvbSubtitleStylePassthrough;
use AsyncAws\MediaConvert\Enum\DvbSubtitleTeletextSpacing;
use AsyncAws\MediaConvert\Enum\DvbSubtitlingType;
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
use AsyncAws\MediaConvert\Enum\EmbeddedConvert608To708;
use AsyncAws\MediaConvert\Enum\EmbeddedTerminateCaptions;
use AsyncAws\MediaConvert\Enum\EmbeddedTimecodeOverride;
use AsyncAws\MediaConvert\Enum\F4vMoovPlacement;
use AsyncAws\MediaConvert\Enum\FileSourceConvert608To708;
use AsyncAws\MediaConvert\Enum\FileSourceTimeDeltaUnits;
use AsyncAws\MediaConvert\Enum\FontScript;
use AsyncAws\MediaConvert\Enum\H264AdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264CodecLevel;
use AsyncAws\MediaConvert\Enum\H264CodecProfile;
use AsyncAws\MediaConvert\Enum\H264DynamicSubGop;
use AsyncAws\MediaConvert\Enum\H264EntropyEncoding;
use AsyncAws\MediaConvert\Enum\H264FieldEncoding;
use AsyncAws\MediaConvert\Enum\H264FlickerAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264FramerateControl;
use AsyncAws\MediaConvert\Enum\H264FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\H264GopBReference;
use AsyncAws\MediaConvert\Enum\H264GopSizeUnits;
use AsyncAws\MediaConvert\Enum\H264InterlaceMode;
use AsyncAws\MediaConvert\Enum\H264ParControl;
use AsyncAws\MediaConvert\Enum\H264QualityTuningLevel;
use AsyncAws\MediaConvert\Enum\H264RateControlMode;
use AsyncAws\MediaConvert\Enum\H264RepeatPps;
use AsyncAws\MediaConvert\Enum\H264ScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\H264SceneChangeDetect;
use AsyncAws\MediaConvert\Enum\H264SlowPal;
use AsyncAws\MediaConvert\Enum\H264SpatialAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264Syntax;
use AsyncAws\MediaConvert\Enum\H264Telecine;
use AsyncAws\MediaConvert\Enum\H264TemporalAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264UnregisteredSeiTimecode;
use AsyncAws\MediaConvert\Enum\H265AdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H265AlternateTransferFunctionSei;
use AsyncAws\MediaConvert\Enum\H265CodecLevel;
use AsyncAws\MediaConvert\Enum\H265CodecProfile;
use AsyncAws\MediaConvert\Enum\H265DynamicSubGop;
use AsyncAws\MediaConvert\Enum\H265FlickerAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H265FramerateControl;
use AsyncAws\MediaConvert\Enum\H265FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\H265GopBReference;
use AsyncAws\MediaConvert\Enum\H265GopSizeUnits;
use AsyncAws\MediaConvert\Enum\H265InterlaceMode;
use AsyncAws\MediaConvert\Enum\H265ParControl;
use AsyncAws\MediaConvert\Enum\H265QualityTuningLevel;
use AsyncAws\MediaConvert\Enum\H265RateControlMode;
use AsyncAws\MediaConvert\Enum\H265SampleAdaptiveOffsetFilterMode;
use AsyncAws\MediaConvert\Enum\H265ScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\H265SceneChangeDetect;
use AsyncAws\MediaConvert\Enum\H265SlowPal;
use AsyncAws\MediaConvert\Enum\H265SpatialAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H265Telecine;
use AsyncAws\MediaConvert\Enum\H265TemporalAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H265TemporalIds;
use AsyncAws\MediaConvert\Enum\H265Tiles;
use AsyncAws\MediaConvert\Enum\H265UnregisteredSeiTimecode;
use AsyncAws\MediaConvert\Enum\H265WriteMp4PackagingType;
use AsyncAws\MediaConvert\Enum\HDRToSDRToneMapper;
use AsyncAws\MediaConvert\Enum\HlsAdMarkers;
use AsyncAws\MediaConvert\Enum\HlsAudioOnlyContainer;
use AsyncAws\MediaConvert\Enum\HlsAudioOnlyHeader;
use AsyncAws\MediaConvert\Enum\HlsAudioTrackType;
use AsyncAws\MediaConvert\Enum\HlsCaptionLanguageSetting;
use AsyncAws\MediaConvert\Enum\HlsCaptionSegmentLengthControl;
use AsyncAws\MediaConvert\Enum\HlsClientCache;
use AsyncAws\MediaConvert\Enum\HlsCodecSpecification;
use AsyncAws\MediaConvert\Enum\HlsDescriptiveVideoServiceFlag;
use AsyncAws\MediaConvert\Enum\HlsDirectoryStructure;
use AsyncAws\MediaConvert\Enum\HlsEncryptionType;
use AsyncAws\MediaConvert\Enum\HlsIFrameOnlyManifest;
use AsyncAws\MediaConvert\Enum\HlsImageBasedTrickPlay;
use AsyncAws\MediaConvert\Enum\HlsInitializationVectorInManifest;
use AsyncAws\MediaConvert\Enum\HlsIntervalCadence;
use AsyncAws\MediaConvert\Enum\HlsKeyProviderType;
use AsyncAws\MediaConvert\Enum\HlsManifestCompression;
use AsyncAws\MediaConvert\Enum\HlsManifestDurationFormat;
use AsyncAws\MediaConvert\Enum\HlsOfflineEncrypted;
use AsyncAws\MediaConvert\Enum\HlsOutputSelection;
use AsyncAws\MediaConvert\Enum\HlsProgramDateTime;
use AsyncAws\MediaConvert\Enum\HlsSegmentControl;
use AsyncAws\MediaConvert\Enum\HlsSegmentLengthControl;
use AsyncAws\MediaConvert\Enum\HlsStreamInfResolution;
use AsyncAws\MediaConvert\Enum\HlsTargetDurationCompatibilityMode;
use AsyncAws\MediaConvert\Enum\HlsTimedMetadataId3Frame;
use AsyncAws\MediaConvert\Enum\ImscAccessibilitySubs;
use AsyncAws\MediaConvert\Enum\ImscStylePassthrough;
use AsyncAws\MediaConvert\Enum\InputDeblockFilter;
use AsyncAws\MediaConvert\Enum\InputDenoiseFilter;
use AsyncAws\MediaConvert\Enum\InputFilterEnable;
use AsyncAws\MediaConvert\Enum\InputPsiControl;
use AsyncAws\MediaConvert\Enum\InputRotate;
use AsyncAws\MediaConvert\Enum\InputSampleRange;
use AsyncAws\MediaConvert\Enum\InputScanType;
use AsyncAws\MediaConvert\Enum\LanguageCode;
use AsyncAws\MediaConvert\Enum\M2tsAudioBufferModel;
use AsyncAws\MediaConvert\Enum\M2tsAudioDuration;
use AsyncAws\MediaConvert\Enum\M2tsBufferModel;
use AsyncAws\MediaConvert\Enum\M2tsDataPtsControl;
use AsyncAws\MediaConvert\Enum\M2tsEbpAudioInterval;
use AsyncAws\MediaConvert\Enum\M2tsEbpPlacement;
use AsyncAws\MediaConvert\Enum\M2tsEsRateInPes;
use AsyncAws\MediaConvert\Enum\M2tsForceTsVideoEbpOrder;
use AsyncAws\MediaConvert\Enum\M2tsKlvMetadata;
use AsyncAws\MediaConvert\Enum\M2tsNielsenId3;
use AsyncAws\MediaConvert\Enum\M2tsPcrControl;
use AsyncAws\MediaConvert\Enum\M2tsRateMode;
use AsyncAws\MediaConvert\Enum\M2tsScte35Source;
use AsyncAws\MediaConvert\Enum\M2tsSegmentationMarkers;
use AsyncAws\MediaConvert\Enum\M2tsSegmentationStyle;
use AsyncAws\MediaConvert\Enum\M3u8AudioDuration;
use AsyncAws\MediaConvert\Enum\M3u8DataPtsControl;
use AsyncAws\MediaConvert\Enum\M3u8NielsenId3;
use AsyncAws\MediaConvert\Enum\M3u8PcrControl;
use AsyncAws\MediaConvert\Enum\M3u8Scte35Source;
use AsyncAws\MediaConvert\Enum\MotionImageInsertionMode;
use AsyncAws\MediaConvert\Enum\MotionImagePlayback;
use AsyncAws\MediaConvert\Enum\MovClapAtom;
use AsyncAws\MediaConvert\Enum\MovCslgAtom;
use AsyncAws\MediaConvert\Enum\MovMpeg2FourCCControl;
use AsyncAws\MediaConvert\Enum\MovPaddingControl;
use AsyncAws\MediaConvert\Enum\MovReference;
use AsyncAws\MediaConvert\Enum\Mp3RateControlMode;
use AsyncAws\MediaConvert\Enum\Mp4CslgAtom;
use AsyncAws\MediaConvert\Enum\Mp4FreeSpaceBox;
use AsyncAws\MediaConvert\Enum\Mp4MoovPlacement;
use AsyncAws\MediaConvert\Enum\MpdAccessibilityCaptionHints;
use AsyncAws\MediaConvert\Enum\MpdAudioDuration;
use AsyncAws\MediaConvert\Enum\MpdCaptionContainerType;
use AsyncAws\MediaConvert\Enum\MpdKlvMetadata;
use AsyncAws\MediaConvert\Enum\MpdManifestMetadataSignaling;
use AsyncAws\MediaConvert\Enum\MpdScte35Esam;
use AsyncAws\MediaConvert\Enum\MpdScte35Source;
use AsyncAws\MediaConvert\Enum\MpdTimedMetadata;
use AsyncAws\MediaConvert\Enum\MpdTimedMetadataBoxVersion;
use AsyncAws\MediaConvert\Enum\Mpeg2AdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\Mpeg2CodecLevel;
use AsyncAws\MediaConvert\Enum\Mpeg2CodecProfile;
use AsyncAws\MediaConvert\Enum\Mpeg2DynamicSubGop;
use AsyncAws\MediaConvert\Enum\Mpeg2FramerateControl;
use AsyncAws\MediaConvert\Enum\Mpeg2FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\Mpeg2GopSizeUnits;
use AsyncAws\MediaConvert\Enum\Mpeg2InterlaceMode;
use AsyncAws\MediaConvert\Enum\Mpeg2IntraDcPrecision;
use AsyncAws\MediaConvert\Enum\Mpeg2ParControl;
use AsyncAws\MediaConvert\Enum\Mpeg2QualityTuningLevel;
use AsyncAws\MediaConvert\Enum\Mpeg2RateControlMode;
use AsyncAws\MediaConvert\Enum\Mpeg2ScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\Mpeg2SceneChangeDetect;
use AsyncAws\MediaConvert\Enum\Mpeg2SlowPal;
use AsyncAws\MediaConvert\Enum\Mpeg2SpatialAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\Mpeg2Syntax;
use AsyncAws\MediaConvert\Enum\Mpeg2Telecine;
use AsyncAws\MediaConvert\Enum\Mpeg2TemporalAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\MsSmoothAudioDeduplication;
use AsyncAws\MediaConvert\Enum\MsSmoothFragmentLengthControl;
use AsyncAws\MediaConvert\Enum\MsSmoothManifestEncoding;
use AsyncAws\MediaConvert\Enum\MxfAfdSignaling;
use AsyncAws\MediaConvert\Enum\MxfProfile;
use AsyncAws\MediaConvert\Enum\MxfXavcDurationMode;
use AsyncAws\MediaConvert\Enum\NielsenActiveWatermarkProcessType;
use AsyncAws\MediaConvert\Enum\NielsenSourceWatermarkStatusType;
use AsyncAws\MediaConvert\Enum\NielsenUniqueTicPerAudioTrackType;
use AsyncAws\MediaConvert\Enum\NoiseFilterPostTemporalSharpening;
use AsyncAws\MediaConvert\Enum\NoiseFilterPostTemporalSharpeningStrength;
use AsyncAws\MediaConvert\Enum\NoiseReducerFilter;
use AsyncAws\MediaConvert\Enum\OutputGroupType;
use AsyncAws\MediaConvert\Enum\OutputSdt;
use AsyncAws\MediaConvert\Enum\PadVideo;
use AsyncAws\MediaConvert\Enum\ProresChromaSampling;
use AsyncAws\MediaConvert\Enum\ProresCodecProfile;
use AsyncAws\MediaConvert\Enum\ProresFramerateControl;
use AsyncAws\MediaConvert\Enum\ProresFramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\ProresInterlaceMode;
use AsyncAws\MediaConvert\Enum\ProresParControl;
use AsyncAws\MediaConvert\Enum\ProresScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\ProresSlowPal;
use AsyncAws\MediaConvert\Enum\ProresTelecine;
use AsyncAws\MediaConvert\Enum\RequiredFlag;
use AsyncAws\MediaConvert\Enum\RespondToAfd;
use AsyncAws\MediaConvert\Enum\RuleType;
use AsyncAws\MediaConvert\Enum\S3ObjectCannedAcl;
use AsyncAws\MediaConvert\Enum\S3ServerSideEncryptionType;
use AsyncAws\MediaConvert\Enum\SampleRangeConversion;
use AsyncAws\MediaConvert\Enum\ScalingBehavior;
use AsyncAws\MediaConvert\Enum\SccDestinationFramerate;
use AsyncAws\MediaConvert\Enum\SimulateReservedQueue;
use AsyncAws\MediaConvert\Enum\SrtStylePassthrough;
use AsyncAws\MediaConvert\Enum\StatusUpdateInterval;
use AsyncAws\MediaConvert\Enum\TeletextPageType;
use AsyncAws\MediaConvert\Enum\TimecodeBurninPosition;
use AsyncAws\MediaConvert\Enum\TimecodeSource;
use AsyncAws\MediaConvert\Enum\TimedMetadata;
use AsyncAws\MediaConvert\Enum\TtmlStylePassthrough;
use AsyncAws\MediaConvert\Enum\Vc3Class;
use AsyncAws\MediaConvert\Enum\Vc3FramerateControl;
use AsyncAws\MediaConvert\Enum\Vc3FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\Vc3InterlaceMode;
use AsyncAws\MediaConvert\Enum\Vc3ScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\Vc3SlowPal;
use AsyncAws\MediaConvert\Enum\Vc3Telecine;
use AsyncAws\MediaConvert\Enum\VchipAction;
use AsyncAws\MediaConvert\Enum\VideoCodec;
use AsyncAws\MediaConvert\Enum\VideoTimecodeInsertion;
use AsyncAws\MediaConvert\Enum\Vp8FramerateControl;
use AsyncAws\MediaConvert\Enum\Vp8FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\Vp8ParControl;
use AsyncAws\MediaConvert\Enum\Vp8QualityTuningLevel;
use AsyncAws\MediaConvert\Enum\Vp8RateControlMode;
use AsyncAws\MediaConvert\Enum\Vp9FramerateControl;
use AsyncAws\MediaConvert\Enum\Vp9FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\Vp9ParControl;
use AsyncAws\MediaConvert\Enum\Vp9QualityTuningLevel;
use AsyncAws\MediaConvert\Enum\Vp9RateControlMode;
use AsyncAws\MediaConvert\Enum\WatermarkingStrength;
use AsyncAws\MediaConvert\Enum\WavFormat;
use AsyncAws\MediaConvert\Enum\WebvttAccessibilitySubs;
use AsyncAws\MediaConvert\Enum\WebvttStylePassthrough;
use AsyncAws\MediaConvert\Enum\Xavc4kIntraCbgProfileClass;
use AsyncAws\MediaConvert\Enum\Xavc4kIntraVbrProfileClass;
use AsyncAws\MediaConvert\Enum\Xavc4kProfileBitrateClass;
use AsyncAws\MediaConvert\Enum\Xavc4kProfileCodecProfile;
use AsyncAws\MediaConvert\Enum\Xavc4kProfileQualityTuningLevel;
use AsyncAws\MediaConvert\Enum\XavcAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\XavcEntropyEncoding;
use AsyncAws\MediaConvert\Enum\XavcFlickerAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\XavcFramerateControl;
use AsyncAws\MediaConvert\Enum\XavcFramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\XavcGopBReference;
use AsyncAws\MediaConvert\Enum\XavcHdIntraCbgProfileClass;
use AsyncAws\MediaConvert\Enum\XavcHdProfileBitrateClass;
use AsyncAws\MediaConvert\Enum\XavcHdProfileQualityTuningLevel;
use AsyncAws\MediaConvert\Enum\XavcHdProfileTelecine;
use AsyncAws\MediaConvert\Enum\XavcInterlaceMode;
use AsyncAws\MediaConvert\Enum\XavcProfile;
use AsyncAws\MediaConvert\Enum\XavcSlowPal;
use AsyncAws\MediaConvert\Enum\XavcSpatialAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\XavcTemporalAdaptiveQuantization;
use AsyncAws\MediaConvert\Input\CreateJobRequest;
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
use AsyncAws\MediaConvert\ValueObject\Eac3AtmosSettings;
use AsyncAws\MediaConvert\ValueObject\Eac3Settings;
use AsyncAws\MediaConvert\ValueObject\EmbeddedDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\EmbeddedSourceSettings;
use AsyncAws\MediaConvert\ValueObject\EsamManifestConfirmConditionNotification;
use AsyncAws\MediaConvert\ValueObject\EsamSettings;
use AsyncAws\MediaConvert\ValueObject\EsamSignalProcessingNotification;
use AsyncAws\MediaConvert\ValueObject\ExtendedDataServices;
use AsyncAws\MediaConvert\ValueObject\F4vSettings;
use AsyncAws\MediaConvert\ValueObject\FileGroupSettings;
use AsyncAws\MediaConvert\ValueObject\FileSourceSettings;
use AsyncAws\MediaConvert\ValueObject\ForceIncludeRenditionSize;
use AsyncAws\MediaConvert\ValueObject\FrameCaptureSettings;
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
use AsyncAws\MediaConvert\ValueObject\OutputGroup;
use AsyncAws\MediaConvert\ValueObject\OutputGroupSettings;
use AsyncAws\MediaConvert\ValueObject\OutputSettings;
use AsyncAws\MediaConvert\ValueObject\PartnerWatermarking;
use AsyncAws\MediaConvert\ValueObject\ProresSettings;
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
use AsyncAws\MediaConvert\ValueObject\TrackSourceSettings;
use AsyncAws\MediaConvert\ValueObject\TtmlDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\Vc3Settings;
use AsyncAws\MediaConvert\ValueObject\VideoCodecSettings;
use AsyncAws\MediaConvert\ValueObject\VideoDescription;
use AsyncAws\MediaConvert\ValueObject\VideoPreprocessor;
use AsyncAws\MediaConvert\ValueObject\VideoSelector;
use AsyncAws\MediaConvert\ValueObject\VorbisSettings;
use AsyncAws\MediaConvert\ValueObject\Vp8Settings;
use AsyncAws\MediaConvert\ValueObject\Vp9Settings;
use AsyncAws\MediaConvert\ValueObject\WavSettings;
use AsyncAws\MediaConvert\ValueObject\WebvttDestinationSettings;
use AsyncAws\MediaConvert\ValueObject\WebvttHlsSourceSettings;
use AsyncAws\MediaConvert\ValueObject\Xavc4kIntraCbgProfileSettings;
use AsyncAws\MediaConvert\ValueObject\Xavc4kIntraVbrProfileSettings;
use AsyncAws\MediaConvert\ValueObject\Xavc4kProfileSettings;
use AsyncAws\MediaConvert\ValueObject\XavcHdIntraCbgProfileSettings;
use AsyncAws\MediaConvert\ValueObject\XavcHdProfileSettings;
use AsyncAws\MediaConvert\ValueObject\XavcSettings;

class CreateJobRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateJobRequest([
            'AccelerationSettings' => new AccelerationSettings([
                'Mode' => AccelerationMode::ENABLED,
            ]),
            'BillingTagsSource' => BillingTagsSource::JOB,
            'ClientRequestToken' => 'change me',
            'HopDestinations' => [new HopDestination([
                'Priority' => 1337,
                'Queue' => 'change me',
                'WaitMinutes' => 1337,
            ])],
            'JobTemplate' => 'change me',
            'Priority' => 1337,
            'Queue' => 'change me',
            'Role' => 'change me',
            'Settings' => new JobSettings([
                'AdAvailOffset' => 1337,
                'AvailBlanking' => new AvailBlanking([
                    'AvailBlankingImage' => 'change me',
                ]),
                'Esam' => new EsamSettings([
                    'ManifestConfirmConditionNotification' => new EsamManifestConfirmConditionNotification([
                        'MccXml' => 'change me',
                    ]),
                    'ResponseSignalPreroll' => 1337,
                    'SignalProcessingNotification' => new EsamSignalProcessingNotification([
                        'SccXml' => 'change me',
                    ]),
                ]),
                'ExtendedDataServices' => new ExtendedDataServices([
                    'CopyProtectionAction' => CopyProtectionAction::PASSTHROUGH,
                    'VchipAction' => VchipAction::PASSTHROUGH,
                ]),
                'Inputs' => [new Input([
                    'AdvancedInputFilter' => AdvancedInputFilter::ENABLED,
                    'AdvancedInputFilterSettings' => new AdvancedInputFilterSettings([
                        'AddTexture' => AdvancedInputFilterAddTexture::DISABLED,
                        'Sharpening' => AdvancedInputFilterSharpen::HIGH,
                    ]),
                    'AudioSelectorGroups' => ['change me' => new AudioSelectorGroup([
                        'AudioSelectorNames' => ['change me'],
                    ])],
                    'AudioSelectors' => ['change me' => new AudioSelector([
                        'AudioDurationCorrection' => AudioDurationCorrection::AUTO,
                        'CustomLanguageCode' => 'change me',
                        'DefaultSelection' => AudioDefaultSelection::DEFAULT,
                        'ExternalAudioFileInput' => 'change me',
                        'HlsRenditionGroupSettings' => new HlsRenditionGroupSettings([
                            'RenditionGroupId' => 'change me',
                            'RenditionLanguageCode' => LanguageCode::ENG,
                            'RenditionName' => 'change me',
                        ]),
                        'LanguageCode' => LanguageCode::ENG,
                        'Offset' => 1337,
                        'Pids' => [1337],
                        'ProgramSelection' => 1337,
                        'RemixSettings' => new RemixSettings([
                            'ChannelMapping' => new ChannelMapping([
                                'OutputChannels' => [new OutputChannelMapping([
                                    'InputChannels' => [1337],
                                    'InputChannelsFineTune' => [1337],
                                ])],
                            ]),
                            'ChannelsIn' => 1337,
                            'ChannelsOut' => 1337,
                        ]),
                        'SelectorType' => AudioSelectorType::HLS_RENDITION_GROUP,
                        'Tracks' => [1337],
                    ])],
                    'CaptionSelectors' => ['change me' => new CaptionSelector([
                        'CustomLanguageCode' => 'change me',
                        'LanguageCode' => LanguageCode::ENG,
                        'SourceSettings' => new CaptionSourceSettings([
                            'AncillarySourceSettings' => new AncillarySourceSettings([
                                'Convert608To708' => AncillaryConvert608To708::UPCONVERT,
                                'SourceAncillaryChannelNumber' => 1337,
                                'TerminateCaptions' => AncillaryTerminateCaptions::END_OF_INPUT,
                            ]),
                            'DvbSubSourceSettings' => new DvbSubSourceSettings([
                                'Pid' => 1337,
                            ]),
                            'EmbeddedSourceSettings' => new EmbeddedSourceSettings([
                                'Convert608To708' => EmbeddedConvert608To708::UPCONVERT,
                                'Source608ChannelNumber' => 1337,
                                'Source608TrackNumber' => 1337,
                                'TerminateCaptions' => EmbeddedTerminateCaptions::END_OF_INPUT,
                            ]),
                            'FileSourceSettings' => new FileSourceSettings([
                                'Convert608To708' => FileSourceConvert608To708::UPCONVERT,
                                'ConvertPaintToPop' => CaptionSourceConvertPaintOnToPopOn::DISABLED,
                                'Framerate' => new CaptionSourceFramerate([
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                ]),
                                'SourceFile' => 'change me',
                                'TimeDelta' => 1337,
                                'TimeDeltaUnits' => FileSourceTimeDeltaUnits::MILLISECONDS,
                            ]),
                            'SourceType' => CaptionSourceType::ANCILLARY,
                            'TeletextSourceSettings' => new TeletextSourceSettings([
                                'PageNumber' => 'change me',
                            ]),
                            'TrackSourceSettings' => new TrackSourceSettings([
                                'TrackNumber' => 1337,
                            ]),
                            'WebvttHlsSourceSettings' => new WebvttHlsSourceSettings([
                                'RenditionGroupId' => 'change me',
                                'RenditionLanguageCode' => LanguageCode::ENG,
                                'RenditionName' => 'change me',
                            ]),
                        ]),
                    ])],
                    'Crop' => new Rectangle([
                        'Height' => 1337,
                        'Width' => 1337,
                        'X' => 1337,
                        'Y' => 1337,
                    ]),
                    'DeblockFilter' => InputDeblockFilter::DISABLED,
                    'DecryptionSettings' => new InputDecryptionSettings([
                        'DecryptionMode' => DecryptionMode::AES_CBC,
                        'EncryptedDecryptionKey' => 'change me',
                        'InitializationVector' => 'change me',
                        'KmsKeyRegion' => 'change me',
                    ]),
                    'DenoiseFilter' => InputDenoiseFilter::DISABLED,
                    'DolbyVisionMetadataXml' => 'change me',
                    'FileInput' => 'change me',
                    'FilterEnable' => InputFilterEnable::AUTO,
                    'FilterStrength' => 1337,
                    'ImageInserter' => new ImageInserter([
                        'InsertableImages' => [new InsertableImage([
                            'Duration' => 1337,
                            'FadeIn' => 1337,
                            'FadeOut' => 1337,
                            'Height' => 1337,
                            'ImageInserterInput' => 'change me',
                            'ImageX' => 1337,
                            'ImageY' => 1337,
                            'Layer' => 1337,
                            'Opacity' => 1337,
                            'StartTime' => 'change me',
                            'Width' => 1337,
                        ])],
                        'SdrReferenceWhiteLevel' => 1337,
                    ]),
                    'InputClippings' => [new InputClipping([
                        'EndTimecode' => 'change me',
                        'StartTimecode' => 'change me',
                    ])],
                    'InputScanType' => InputScanType::AUTO,
                    'Position' => new Rectangle([
                        'Height' => 1337,
                        'Width' => 1337,
                        'X' => 1337,
                        'Y' => 1337,
                    ]),
                    'ProgramNumber' => 1337,
                    'PsiControl' => InputPsiControl::IGNORE_PSI,
                    'SupplementalImps' => ['change me'],
                    'TimecodeSource' => TimecodeSource::EMBEDDED,
                    'TimecodeStart' => 'change me',
                    'VideoGenerator' => new InputVideoGenerator([
                        'Duration' => 1337,
                    ]),
                    'VideoSelector' => new VideoSelector([
                        'AlphaBehavior' => AlphaBehavior::DISCARD,
                        'ColorSpace' => ColorSpace::HDR10,
                        'ColorSpaceUsage' => ColorSpaceUsage::FALLBACK,
                        'EmbeddedTimecodeOverride' => EmbeddedTimecodeOverride::NONE,
                        'Hdr10Metadata' => new Hdr10Metadata([
                            'BluePrimaryX' => 1337,
                            'BluePrimaryY' => 1337,
                            'GreenPrimaryX' => 1337,
                            'GreenPrimaryY' => 1337,
                            'MaxContentLightLevel' => 1337,
                            'MaxFrameAverageLightLevel' => 1337,
                            'MaxLuminance' => 1337,
                            'MinLuminance' => 1337,
                            'RedPrimaryX' => 1337,
                            'RedPrimaryY' => 1337,
                            'WhitePointX' => 1337,
                            'WhitePointY' => 1337,
                        ]),
                        'PadVideo' => PadVideo::BLACK,
                        'Pid' => 1337,
                        'ProgramNumber' => 1337,
                        'Rotate' => InputRotate::AUTO,
                        'SampleRange' => InputSampleRange::FULL_RANGE,
                    ]),
                ])],
                'KantarWatermark' => new KantarWatermarkSettings([
                    'ChannelName' => 'change me',
                    'ContentReference' => 'change me',
                    'CredentialsSecretName' => 'change me',
                    'FileOffset' => 1337,
                    'KantarLicenseId' => 1337,
                    'KantarServerUrl' => 'change me',
                    'LogDestination' => 'change me',
                    'Metadata3' => 'change me',
                    'Metadata4' => 'change me',
                    'Metadata5' => 'change me',
                    'Metadata6' => 'change me',
                    'Metadata7' => 'change me',
                    'Metadata8' => 'change me',
                ]),
                'MotionImageInserter' => new MotionImageInserter([
                    'Framerate' => new MotionImageInsertionFramerate([
                        'FramerateDenominator' => 1337,
                        'FramerateNumerator' => 1337,
                    ]),
                    'Input' => 'change me',
                    'InsertionMode' => MotionImageInsertionMode::PNG,
                    'Offset' => new MotionImageInsertionOffset([
                        'ImageX' => 1337,
                        'ImageY' => 1337,
                    ]),
                    'Playback' => MotionImagePlayback::ONCE,
                    'StartTime' => 'change me',
                ]),
                'NielsenConfiguration' => new NielsenConfiguration([
                    'BreakoutCode' => 1337,
                    'DistributorId' => 'change me',
                ]),
                'NielsenNonLinearWatermark' => new NielsenNonLinearWatermarkSettings([
                    'ActiveWatermarkProcess' => NielsenActiveWatermarkProcessType::CBET,
                    'AdiFilename' => 'change me',
                    'AssetId' => 'change me',
                    'AssetName' => 'change me',
                    'CbetSourceId' => 'change me',
                    'EpisodeId' => 'change me',
                    'MetadataDestination' => 'change me',
                    'SourceId' => 1337,
                    'SourceWatermarkStatus' => NielsenSourceWatermarkStatusType::CLEAN,
                    'TicServerUrl' => 'change me',
                    'UniqueTicPerAudioTrack' => NielsenUniqueTicPerAudioTrackType::SAME_TICS_PER_TRACK,
                ]),
                'OutputGroups' => [new OutputGroup([
                    'AutomatedEncodingSettings' => new AutomatedEncodingSettings([
                        'AbrSettings' => new AutomatedAbrSettings([
                            'MaxAbrBitrate' => 1337,
                            'MaxRenditions' => 1337,
                            'MinAbrBitrate' => 1337,
                            'Rules' => [new AutomatedAbrRule([
                                'AllowedRenditions' => [new AllowedRenditionSize([
                                    'Height' => 1337,
                                    'Required' => RequiredFlag::ENABLED,
                                    'Width' => 1337,
                                ])],
                                'ForceIncludeRenditions' => [new ForceIncludeRenditionSize([
                                    'Height' => 1337,
                                    'Width' => 1337,
                                ])],
                                'MinBottomRenditionSize' => new MinBottomRenditionSize([
                                    'Height' => 1337,
                                    'Width' => 1337,
                                ]),
                                'MinTopRenditionSize' => new MinTopRenditionSize([
                                    'Height' => 1337,
                                    'Width' => 1337,
                                ]),
                                'Type' => RuleType::ALLOWED_RENDITIONS,
                            ])],
                        ]),
                    ]),
                    'CustomName' => 'change me',
                    'Name' => 'change me',
                    'OutputGroupSettings' => new OutputGroupSettings([
                        'CmafGroupSettings' => new CmafGroupSettings([
                            'AdditionalManifests' => [new CmafAdditionalManifest([
                                'ManifestNameModifier' => 'change me',
                                'SelectedOutputs' => ['change me'],
                            ])],
                            'BaseUrl' => 'change me',
                            'ClientCache' => CmafClientCache::DISABLED,
                            'CodecSpecification' => CmafCodecSpecification::RFC_6381,
                            'DashManifestStyle' => DashManifestStyle::BASIC,
                            'Destination' => 'change me',
                            'DestinationSettings' => new DestinationSettings([
                                'S3Settings' => new S3DestinationSettings([
                                    'AccessControl' => new S3DestinationAccessControl([
                                        'CannedAcl' => S3ObjectCannedAcl::PUBLIC_READ,
                                    ]),
                                    'Encryption' => new S3EncryptionSettings([
                                        'EncryptionType' => S3ServerSideEncryptionType::SERVER_SIDE_ENCRYPTION_S3,
                                        'KmsEncryptionContext' => 'change me',
                                        'KmsKeyArn' => 'change me',
                                    ]),
                                ]),
                            ]),
                            'Encryption' => new CmafEncryptionSettings([
                                'ConstantInitializationVector' => 'change me',
                                'EncryptionMethod' => CmafEncryptionType::AES_CTR,
                                'InitializationVectorInManifest' => CmafInitializationVectorInManifest::EXCLUDE,
                                'SpekeKeyProvider' => new SpekeKeyProviderCmaf([
                                    'CertificateArn' => 'change me',
                                    'DashSignaledSystemIds' => ['change me'],
                                    'HlsSignaledSystemIds' => ['change me'],
                                    'ResourceId' => 'change me',
                                    'Url' => 'change me',
                                ]),
                                'StaticKeyProvider' => new StaticKeyProvider([
                                    'KeyFormat' => 'change me',
                                    'KeyFormatVersions' => 'change me',
                                    'StaticKeyValue' => 'change me',
                                    'Url' => 'change me',
                                ]),
                                'Type' => CmafKeyProviderType::STATIC_KEY,
                            ]),
                            'FragmentLength' => 1337,
                            'ImageBasedTrickPlay' => CmafImageBasedTrickPlay::NONE,
                            'ImageBasedTrickPlaySettings' => new CmafImageBasedTrickPlaySettings([
                                'IntervalCadence' => CmafIntervalCadence::FOLLOW_IFRAME,
                                'ThumbnailHeight' => 1337,
                                'ThumbnailInterval' => 1337,
                                'ThumbnailWidth' => 1337,
                                'TileHeight' => 1337,
                                'TileWidth' => 1337,
                            ]),
                            'ManifestCompression' => CmafManifestCompression::GZIP,
                            'ManifestDurationFormat' => CmafManifestDurationFormat::INTEGER,
                            'MinBufferTime' => 1337,
                            'MinFinalSegmentLength' => 1337,
                            'MpdManifestBandwidthType' => CmafMpdManifestBandwidthType::MAX,
                            'MpdProfile' => CmafMpdProfile::MAIN_PROFILE,
                            'PtsOffsetHandlingForBFrames' => CmafPtsOffsetHandlingForBFrames::MATCH_INITIAL_PTS,
                            'SegmentControl' => CmafSegmentControl::SINGLE_FILE,
                            'SegmentLength' => 1337,
                            'SegmentLengthControl' => CmafSegmentLengthControl::EXACT,
                            'StreamInfResolution' => CmafStreamInfResolution::EXCLUDE,
                            'TargetDurationCompatibilityMode' => CmafTargetDurationCompatibilityMode::SPEC_COMPLIANT,
                            'VideoCompositionOffsets' => CmafVideoCompositionOffsets::SIGNED,
                            'WriteDashManifest' => CmafWriteDASHManifest::DISABLED,
                            'WriteHlsManifest' => CmafWriteHLSManifest::DISABLED,
                            'WriteSegmentTimelineInRepresentation' => CmafWriteSegmentTimelineInRepresentation::DISABLED,
                        ]),
                        'DashIsoGroupSettings' => new DashIsoGroupSettings([
                            'AdditionalManifests' => [new DashAdditionalManifest([
                                'ManifestNameModifier' => 'change me',
                                'SelectedOutputs' => ['change me'],
                            ])],
                            'AudioChannelConfigSchemeIdUri' => DashIsoGroupAudioChannelConfigSchemeIdUri::DOLBY_CHANNEL_CONFIGURATION,
                            'BaseUrl' => 'change me',
                            'DashManifestStyle' => DashManifestStyle::BASIC,
                            'Destination' => 'change me',
                            'DestinationSettings' => new DestinationSettings([
                                'S3Settings' => new S3DestinationSettings([
                                    'AccessControl' => new S3DestinationAccessControl([
                                        'CannedAcl' => S3ObjectCannedAcl::PUBLIC_READ,
                                    ]),
                                    'Encryption' => new S3EncryptionSettings([
                                        'EncryptionType' => S3ServerSideEncryptionType::SERVER_SIDE_ENCRYPTION_S3,
                                        'KmsEncryptionContext' => 'change me',
                                        'KmsKeyArn' => 'change me',
                                    ]),
                                ]),
                            ]),
                            'Encryption' => new DashIsoEncryptionSettings([
                                'PlaybackDeviceCompatibility' => DashIsoPlaybackDeviceCompatibility::CENC_V1,
                                'SpekeKeyProvider' => new SpekeKeyProvider([
                                    'CertificateArn' => 'change me',
                                    'ResourceId' => 'change me',
                                    'SystemIds' => ['change me'],
                                    'Url' => 'change me',
                                ]),
                            ]),
                            'FragmentLength' => 1337,
                            'HbbtvCompliance' => DashIsoHbbtvCompliance::NONE,
                            'ImageBasedTrickPlay' => DashIsoImageBasedTrickPlay::NONE,
                            'ImageBasedTrickPlaySettings' => new DashIsoImageBasedTrickPlaySettings([
                                'IntervalCadence' => DashIsoIntervalCadence::FOLLOW_IFRAME,
                                'ThumbnailHeight' => 1337,
                                'ThumbnailInterval' => 1337,
                                'ThumbnailWidth' => 1337,
                                'TileHeight' => 1337,
                                'TileWidth' => 1337,
                            ]),
                            'MinBufferTime' => 1337,
                            'MinFinalSegmentLength' => 1337,
                            'MpdManifestBandwidthType' => DashIsoMpdManifestBandwidthType::MAX,
                            'MpdProfile' => DashIsoMpdProfile::MAIN_PROFILE,
                            'PtsOffsetHandlingForBFrames' => DashIsoPtsOffsetHandlingForBFrames::MATCH_INITIAL_PTS,
                            'SegmentControl' => DashIsoSegmentControl::SINGLE_FILE,
                            'SegmentLength' => 1337,
                            'SegmentLengthControl' => DashIsoSegmentLengthControl::EXACT,
                            'VideoCompositionOffsets' => DashIsoVideoCompositionOffsets::SIGNED,
                            'WriteSegmentTimelineInRepresentation' => DashIsoWriteSegmentTimelineInRepresentation::DISABLED,
                        ]),
                        'FileGroupSettings' => new FileGroupSettings([
                            'Destination' => 'change me',
                            'DestinationSettings' => new DestinationSettings([
                                'S3Settings' => new S3DestinationSettings([
                                    'AccessControl' => new S3DestinationAccessControl([
                                        'CannedAcl' => S3ObjectCannedAcl::PUBLIC_READ,
                                    ]),
                                    'Encryption' => new S3EncryptionSettings([
                                        'EncryptionType' => S3ServerSideEncryptionType::SERVER_SIDE_ENCRYPTION_S3,
                                        'KmsEncryptionContext' => 'change me',
                                        'KmsKeyArn' => 'change me',
                                    ]),
                                ]),
                            ]),
                        ]),
                        'HlsGroupSettings' => new HlsGroupSettings([
                            'AdMarkers' => [HlsAdMarkers::ELEMENTAL],
                            'AdditionalManifests' => [new HlsAdditionalManifest([
                                'ManifestNameModifier' => 'change me',
                                'SelectedOutputs' => ['change me'],
                            ])],
                            'AudioOnlyHeader' => HlsAudioOnlyHeader::EXCLUDE,
                            'BaseUrl' => 'change me',
                            'CaptionLanguageMappings' => [new HlsCaptionLanguageMapping([
                                'CaptionChannel' => 1337,
                                'CustomLanguageCode' => 'change me',
                                'LanguageCode' => LanguageCode::ENG,
                                'LanguageDescription' => 'change me',
                            ])],
                            'CaptionLanguageSetting' => HlsCaptionLanguageSetting::NONE,
                            'CaptionSegmentLengthControl' => HlsCaptionSegmentLengthControl::LARGE_SEGMENTS,
                            'ClientCache' => HlsClientCache::DISABLED,
                            'CodecSpecification' => HlsCodecSpecification::RFC_6381,
                            'Destination' => 'change me',
                            'DestinationSettings' => new DestinationSettings([
                                'S3Settings' => new S3DestinationSettings([
                                    'AccessControl' => new S3DestinationAccessControl([
                                        'CannedAcl' => S3ObjectCannedAcl::PUBLIC_READ,
                                    ]),
                                    'Encryption' => new S3EncryptionSettings([
                                        'EncryptionType' => S3ServerSideEncryptionType::SERVER_SIDE_ENCRYPTION_S3,
                                        'KmsEncryptionContext' => 'change me',
                                        'KmsKeyArn' => 'change me',
                                    ]),
                                ]),
                            ]),
                            'DirectoryStructure' => HlsDirectoryStructure::SINGLE_DIRECTORY,
                            'Encryption' => new HlsEncryptionSettings([
                                'ConstantInitializationVector' => 'change me',
                                'EncryptionMethod' => HlsEncryptionType::AES128,
                                'InitializationVectorInManifest' => HlsInitializationVectorInManifest::EXCLUDE,
                                'OfflineEncrypted' => HlsOfflineEncrypted::DISABLED,
                                'SpekeKeyProvider' => new SpekeKeyProvider([
                                    'CertificateArn' => 'change me',
                                    'ResourceId' => 'change me',
                                    'SystemIds' => ['change me'],
                                    'Url' => 'change me',
                                ]),
                                'StaticKeyProvider' => new StaticKeyProvider([
                                    'KeyFormat' => 'change me',
                                    'KeyFormatVersions' => 'change me',
                                    'StaticKeyValue' => 'change me',
                                    'Url' => 'change me',
                                ]),
                                'Type' => HlsKeyProviderType::STATIC_KEY,
                            ]),
                            'ImageBasedTrickPlay' => HlsImageBasedTrickPlay::NONE,
                            'ImageBasedTrickPlaySettings' => new HlsImageBasedTrickPlaySettings([
                                'IntervalCadence' => HlsIntervalCadence::FOLLOW_IFRAME,
                                'ThumbnailHeight' => 1337,
                                'ThumbnailInterval' => 1337,
                                'ThumbnailWidth' => 1337,
                                'TileHeight' => 1337,
                                'TileWidth' => 1337,
                            ]),
                            'ManifestCompression' => HlsManifestCompression::NONE,
                            'ManifestDurationFormat' => HlsManifestDurationFormat::INTEGER,
                            'MinFinalSegmentLength' => 1337,
                            'MinSegmentLength' => 1337,
                            'OutputSelection' => HlsOutputSelection::SEGMENTS_ONLY,
                            'ProgramDateTime' => HlsProgramDateTime::EXCLUDE,
                            'ProgramDateTimePeriod' => 1337,
                            'SegmentControl' => HlsSegmentControl::SINGLE_FILE,
                            'SegmentLength' => 1337,
                            'SegmentLengthControl' => HlsSegmentLengthControl::EXACT,
                            'SegmentsPerSubdirectory' => 1337,
                            'StreamInfResolution' => HlsStreamInfResolution::EXCLUDE,
                            'TargetDurationCompatibilityMode' => HlsTargetDurationCompatibilityMode::SPEC_COMPLIANT,
                            'TimedMetadataId3Frame' => HlsTimedMetadataId3Frame::NONE,
                            'TimedMetadataId3Period' => 1337,
                            'TimestampDeltaMilliseconds' => 1337,
                        ]),
                        'MsSmoothGroupSettings' => new MsSmoothGroupSettings([
                            'AdditionalManifests' => [new MsSmoothAdditionalManifest([
                                'ManifestNameModifier' => 'change me',
                                'SelectedOutputs' => ['change me'],
                            ])],
                            'AudioDeduplication' => MsSmoothAudioDeduplication::NONE,
                            'Destination' => 'change me',
                            'DestinationSettings' => new DestinationSettings([
                                'S3Settings' => new S3DestinationSettings([
                                    'AccessControl' => new S3DestinationAccessControl([
                                        'CannedAcl' => S3ObjectCannedAcl::PUBLIC_READ,
                                    ]),
                                    'Encryption' => new S3EncryptionSettings([
                                        'EncryptionType' => S3ServerSideEncryptionType::SERVER_SIDE_ENCRYPTION_S3,
                                        'KmsEncryptionContext' => 'change me',
                                        'KmsKeyArn' => 'change me',
                                    ]),
                                ]),
                            ]),
                            'Encryption' => new MsSmoothEncryptionSettings([
                                'SpekeKeyProvider' => new SpekeKeyProvider([
                                    'CertificateArn' => 'change me',
                                    'ResourceId' => 'change me',
                                    'SystemIds' => ['change me'],
                                    'Url' => 'change me',
                                ]),
                            ]),
                            'FragmentLength' => 1337,
                            'FragmentLengthControl' => MsSmoothFragmentLengthControl::EXACT,
                            'ManifestEncoding' => MsSmoothManifestEncoding::UTF8,
                        ]),
                        'Type' => OutputGroupType::CMAF_GROUP_SETTINGS,
                    ]),
                    'Outputs' => [new Output([
                        'AudioDescriptions' => [new AudioDescription([
                            'AudioChannelTaggingSettings' => new AudioChannelTaggingSettings([
                                'ChannelTag' => AudioChannelTag::C,
                            ]),
                            'AudioNormalizationSettings' => new AudioNormalizationSettings([
                                'Algorithm' => AudioNormalizationAlgorithm::ITU_BS_1770_4,
                                'AlgorithmControl' => AudioNormalizationAlgorithmControl::CORRECT_AUDIO,
                                'CorrectionGateLevel' => 1337,
                                'LoudnessLogging' => AudioNormalizationLoudnessLogging::LOG,
                                'PeakCalculation' => AudioNormalizationPeakCalculation::NONE,
                                'TargetLkfs' => 1337,
                                'TruePeakLimiterThreshold' => 1337,
                            ]),
                            'AudioSourceName' => 'change me',
                            'AudioType' => 1337,
                            'AudioTypeControl' => AudioTypeControl::FOLLOW_INPUT,
                            'CodecSettings' => new AudioCodecSettings([
                                'AacSettings' => new AacSettings([
                                    'AudioDescriptionBroadcasterMix' => AacAudioDescriptionBroadcasterMix::NORMAL,
                                    'Bitrate' => 1337,
                                    'CodecProfile' => AacCodecProfile::HEV1,
                                    'CodingMode' => AacCodingMode::CODING_MODE_1_0,
                                    'RateControlMode' => AacRateControlMode::CBR,
                                    'RawFormat' => AacRawFormat::NONE,
                                    'SampleRate' => 1337,
                                    'Specification' => AacSpecification::MPEG4,
                                    'VbrQuality' => AacVbrQuality::HIGH,
                                ]),
                                'Ac3Settings' => new Ac3Settings([
                                    'Bitrate' => 1337,
                                    'BitstreamMode' => Ac3BitstreamMode::COMMENTARY,
                                    'CodingMode' => Ac3CodingMode::CODING_MODE_1_0,
                                    'Dialnorm' => 1337,
                                    'DynamicRangeCompressionLine' => Ac3DynamicRangeCompressionLine::NONE,
                                    'DynamicRangeCompressionProfile' => Ac3DynamicRangeCompressionProfile::NONE,
                                    'DynamicRangeCompressionRf' => Ac3DynamicRangeCompressionRf::NONE,
                                    'LfeFilter' => Ac3LfeFilter::DISABLED,
                                    'MetadataControl' => Ac3MetadataControl::FOLLOW_INPUT,
                                    'SampleRate' => 1337,
                                ]),
                                'AiffSettings' => new AiffSettings([
                                    'BitDepth' => 1337,
                                    'Channels' => 1337,
                                    'SampleRate' => 1337,
                                ]),
                                'Codec' => AudioCodec::AAC,
                                'Eac3AtmosSettings' => new Eac3AtmosSettings([
                                    'Bitrate' => 1337,
                                    'BitstreamMode' => Eac3AtmosBitstreamMode::COMPLETE_MAIN,
                                    'CodingMode' => Eac3AtmosCodingMode::CODING_MODE_5_1_4,
                                    'DialogueIntelligence' => Eac3AtmosDialogueIntelligence::DISABLED,
                                    'DownmixControl' => Eac3AtmosDownmixControl::SPECIFIED,
                                    'DynamicRangeCompressionLine' => Eac3AtmosDynamicRangeCompressionLine::NONE,
                                    'DynamicRangeCompressionRf' => Eac3AtmosDynamicRangeCompressionRf::NONE,
                                    'DynamicRangeControl' => Eac3AtmosDynamicRangeControl::SPECIFIED,
                                    'LoRoCenterMixLevel' => 1337,
                                    'LoRoSurroundMixLevel' => 1337,
                                    'LtRtCenterMixLevel' => 1337,
                                    'LtRtSurroundMixLevel' => 1337,
                                    'MeteringMode' => Eac3AtmosMeteringMode::ITU_BS_1770_4,
                                    'SampleRate' => 1337,
                                    'SpeechThreshold' => 1337,
                                    'StereoDownmix' => Eac3AtmosStereoDownmix::STEREO,
                                    'SurroundExMode' => Eac3AtmosSurroundExMode::DISABLED,
                                ]),
                                'Eac3Settings' => new Eac3Settings([
                                    'AttenuationControl' => Eac3AttenuationControl::NONE,
                                    'Bitrate' => 1337,
                                    'BitstreamMode' => Eac3BitstreamMode::COMPLETE_MAIN,
                                    'CodingMode' => Eac3CodingMode::CODING_MODE_1_0,
                                    'DcFilter' => Eac3DcFilter::DISABLED,
                                    'Dialnorm' => 1337,
                                    'DynamicRangeCompressionLine' => Eac3DynamicRangeCompressionLine::NONE,
                                    'DynamicRangeCompressionRf' => Eac3DynamicRangeCompressionRf::NONE,
                                    'LfeControl' => Eac3LfeControl::LFE,
                                    'LfeFilter' => Eac3LfeFilter::DISABLED,
                                    'LoRoCenterMixLevel' => 1337,
                                    'LoRoSurroundMixLevel' => 1337,
                                    'LtRtCenterMixLevel' => 1337,
                                    'LtRtSurroundMixLevel' => 1337,
                                    'MetadataControl' => Eac3MetadataControl::FOLLOW_INPUT,
                                    'PassthroughControl' => Eac3PassthroughControl::NO_PASSTHROUGH,
                                    'PhaseControl' => Eac3PhaseControl::NO_SHIFT,
                                    'SampleRate' => 1337,
                                    'StereoDownmix' => Eac3StereoDownmix::DPL2,
                                    'SurroundExMode' => Eac3SurroundExMode::DISABLED,
                                    'SurroundMode' => Eac3SurroundMode::DISABLED,
                                ]),
                                'Mp2Settings' => new Mp2Settings([
                                    'Bitrate' => 1337,
                                    'Channels' => 1337,
                                    'SampleRate' => 1337,
                                ]),
                                'Mp3Settings' => new Mp3Settings([
                                    'Bitrate' => 1337,
                                    'Channels' => 1337,
                                    'RateControlMode' => Mp3RateControlMode::CBR,
                                    'SampleRate' => 1337,
                                    'VbrQuality' => 1337,
                                ]),
                                'OpusSettings' => new OpusSettings([
                                    'Bitrate' => 1337,
                                    'Channels' => 1337,
                                    'SampleRate' => 1337,
                                ]),
                                'VorbisSettings' => new VorbisSettings([
                                    'Channels' => 1337,
                                    'SampleRate' => 1337,
                                    'VbrQuality' => 1337,
                                ]),
                                'WavSettings' => new WavSettings([
                                    'BitDepth' => 1337,
                                    'Channels' => 1337,
                                    'Format' => WavFormat::RIFF,
                                    'SampleRate' => 1337,
                                ]),
                            ]),
                            'CustomLanguageCode' => 'change me',
                            'LanguageCode' => LanguageCode::ENG,
                            'LanguageCodeControl' => AudioLanguageCodeControl::FOLLOW_INPUT,
                            'RemixSettings' => new RemixSettings([
                                'ChannelMapping' => new ChannelMapping([
                                    'OutputChannels' => [new OutputChannelMapping([
                                        'InputChannels' => [1337],
                                        'InputChannelsFineTune' => [1337],
                                    ])],
                                ]),
                                'ChannelsIn' => 1337,
                                'ChannelsOut' => 1337,
                            ]),
                            'StreamName' => 'change me',
                        ])],
                        'CaptionDescriptions' => [new CaptionDescription([
                            'CaptionSelectorName' => 'change me',
                            'CustomLanguageCode' => 'change me',
                            'DestinationSettings' => new CaptionDestinationSettings([
                                'BurninDestinationSettings' => new BurninDestinationSettings([
                                    'Alignment' => BurninSubtitleAlignment::AUTO,
                                    'ApplyFontColor' => BurninSubtitleApplyFontColor::ALL_TEXT,
                                    'BackgroundColor' => BurninSubtitleBackgroundColor::AUTO,
                                    'BackgroundOpacity' => 1337,
                                    'FallbackFont' => BurninSubtitleFallbackFont::BEST_MATCH,
                                    'FontColor' => BurninSubtitleFontColor::AUTO,
                                    'FontOpacity' => 1337,
                                    'FontResolution' => 1337,
                                    'FontScript' => FontScript::AUTOMATIC,
                                    'FontSize' => 1337,
                                    'HexFontColor' => 'change me',
                                    'OutlineColor' => BurninSubtitleOutlineColor::AUTO,
                                    'OutlineSize' => 1337,
                                    'ShadowColor' => BurninSubtitleShadowColor::AUTO,
                                    'ShadowOpacity' => 1337,
                                    'ShadowXOffset' => 1337,
                                    'ShadowYOffset' => 1337,
                                    'StylePassthrough' => BurnInSubtitleStylePassthrough::DISABLED,
                                    'TeletextSpacing' => BurninSubtitleTeletextSpacing::AUTO,
                                    'XPosition' => 1337,
                                    'YPosition' => 1337,
                                ]),
                                'DestinationType' => CaptionDestinationType::EMBEDDED,
                                'DvbSubDestinationSettings' => new DvbSubDestinationSettings([
                                    'Alignment' => DvbSubtitleAlignment::AUTO,
                                    'ApplyFontColor' => DvbSubtitleApplyFontColor::ALL_TEXT,
                                    'BackgroundColor' => DvbSubtitleBackgroundColor::AUTO,
                                    'BackgroundOpacity' => 1337,
                                    'DdsHandling' => DvbddsHandling::NONE,
                                    'DdsXCoordinate' => 1337,
                                    'DdsYCoordinate' => 1337,
                                    'FallbackFont' => DvbSubSubtitleFallbackFont::BEST_MATCH,
                                    'FontColor' => DvbSubtitleFontColor::AUTO,
                                    'FontOpacity' => 1337,
                                    'FontResolution' => 1337,
                                    'FontScript' => FontScript::AUTOMATIC,
                                    'FontSize' => 1337,
                                    'Height' => 1337,
                                    'HexFontColor' => 'change me',
                                    'OutlineColor' => DvbSubtitleOutlineColor::AUTO,
                                    'OutlineSize' => 1337,
                                    'ShadowColor' => DvbSubtitleShadowColor::AUTO,
                                    'ShadowOpacity' => 1337,
                                    'ShadowXOffset' => 1337,
                                    'ShadowYOffset' => 1337,
                                    'StylePassthrough' => DvbSubtitleStylePassthrough::DISABLED,
                                    'SubtitlingType' => DvbSubtitlingType::STANDARD,
                                    'TeletextSpacing' => DvbSubtitleTeletextSpacing::AUTO,
                                    'Width' => 1337,
                                    'XPosition' => 1337,
                                    'YPosition' => 1337,
                                ]),
                                'EmbeddedDestinationSettings' => new EmbeddedDestinationSettings([
                                    'Destination608ChannelNumber' => 1337,
                                    'Destination708ServiceNumber' => 1337,
                                ]),
                                'ImscDestinationSettings' => new ImscDestinationSettings([
                                    'Accessibility' => ImscAccessibilitySubs::DISABLED,
                                    'StylePassthrough' => ImscStylePassthrough::DISABLED,
                                ]),
                                'SccDestinationSettings' => new SccDestinationSettings([
                                    'Framerate' => SccDestinationFramerate::FRAMERATE_23_97,
                                ]),
                                'SrtDestinationSettings' => new SrtDestinationSettings([
                                    'StylePassthrough' => SrtStylePassthrough::DISABLED,
                                ]),
                                'TeletextDestinationSettings' => new TeletextDestinationSettings([
                                    'PageNumber' => 'change me',
                                    'PageTypes' => [TeletextPageType::PAGE_TYPE_INITIAL],
                                ]),
                                'TtmlDestinationSettings' => new TtmlDestinationSettings([
                                    'StylePassthrough' => TtmlStylePassthrough::DISABLED,
                                ]),
                                'WebvttDestinationSettings' => new WebvttDestinationSettings([
                                    'Accessibility' => WebvttAccessibilitySubs::DISABLED,
                                    'StylePassthrough' => WebvttStylePassthrough::DISABLED,
                                ]),
                            ]),
                            'LanguageCode' => LanguageCode::ENG,
                            'LanguageDescription' => 'change me',
                        ])],
                        'ContainerSettings' => new ContainerSettings([
                            'CmfcSettings' => new CmfcSettings([
                                'AudioDuration' => CmfcAudioDuration::DEFAULT_CODEC_DURATION,
                                'AudioGroupId' => 'change me',
                                'AudioRenditionSets' => 'change me',
                                'AudioTrackType' => CmfcAudioTrackType::ALTERNATE_AUDIO_AUTO_SELECT,
                                'DescriptiveVideoServiceFlag' => CmfcDescriptiveVideoServiceFlag::FLAG,
                                'IFrameOnlyManifest' => CmfcIFrameOnlyManifest::EXCLUDE,
                                'KlvMetadata' => CmfcKlvMetadata::NONE,
                                'ManifestMetadataSignaling' => CmfcManifestMetadataSignaling::DISABLED,
                                'Scte35Esam' => CmfcScte35Esam::NONE,
                                'Scte35Source' => CmfcScte35Source::NONE,
                                'TimedMetadata' => CmfcTimedMetadata::NONE,
                                'TimedMetadataBoxVersion' => CmfcTimedMetadataBoxVersion::VERSION_0,
                                'TimedMetadataSchemeIdUri' => 'change me',
                                'TimedMetadataValue' => 'change me',
                            ]),
                            'Container' => ContainerType::CMFC,
                            'F4vSettings' => new F4vSettings([
                                'MoovPlacement' => F4vMoovPlacement::NORMAL,
                            ]),
                            'M2tsSettings' => new M2tsSettings([
                                'AudioBufferModel' => M2tsAudioBufferModel::ATSC,
                                'AudioDuration' => M2tsAudioDuration::DEFAULT_CODEC_DURATION,
                                'AudioFramesPerPes' => 1337,
                                'AudioPids' => [1337],
                                'Bitrate' => 1337,
                                'BufferModel' => M2tsBufferModel::NONE,
                                'DataPTSControl' => M2tsDataPtsControl::AUTO,
                                'DvbNitSettings' => new DvbNitSettings([
                                    'NetworkId' => 1337,
                                    'NetworkName' => 'change me',
                                    'NitInterval' => 1337,
                                ]),
                                'DvbSdtSettings' => new DvbSdtSettings([
                                    'OutputSdt' => OutputSdt::SDT_NONE,
                                    'SdtInterval' => 1337,
                                    'ServiceName' => 'change me',
                                    'ServiceProviderName' => 'change me',
                                ]),
                                'DvbSubPids' => [1337],
                                'DvbTdtSettings' => new DvbTdtSettings([
                                    'TdtInterval' => 1337,
                                ]),
                                'DvbTeletextPid' => 1337,
                                'EbpAudioInterval' => M2tsEbpAudioInterval::VIDEO_INTERVAL,
                                'EbpPlacement' => M2tsEbpPlacement::VIDEO_PID,
                                'EsRateInPes' => M2tsEsRateInPes::EXCLUDE,
                                'ForceTsVideoEbpOrder' => M2tsForceTsVideoEbpOrder::FORCE,
                                'FragmentTime' => 1337,
                                'KlvMetadata' => M2tsKlvMetadata::NONE,
                                'MaxPcrInterval' => 1337,
                                'MinEbpInterval' => 1337,
                                'NielsenId3' => M2tsNielsenId3::NONE,
                                'NullPacketBitrate' => 1337,
                                'PatInterval' => 1337,
                                'PcrControl' => M2tsPcrControl::CONFIGURED_PCR_PERIOD,
                                'PcrPid' => 1337,
                                'PmtInterval' => 1337,
                                'PmtPid' => 1337,
                                'PrivateMetadataPid' => 1337,
                                'ProgramNumber' => 1337,
                                'RateMode' => M2tsRateMode::CBR,
                                'Scte35Esam' => new M2tsScte35Esam([
                                    'Scte35EsamPid' => 1337,
                                ]),
                                'Scte35Pid' => 1337,
                                'Scte35Source' => M2tsScte35Source::NONE,
                                'SegmentationMarkers' => M2tsSegmentationMarkers::NONE,
                                'SegmentationStyle' => M2tsSegmentationStyle::MAINTAIN_CADENCE,
                                'SegmentationTime' => 1337,
                                'TimedMetadataPid' => 1337,
                                'TransportStreamId' => 1337,
                                'VideoPid' => 1337,
                            ]),
                            'M3u8Settings' => new M3u8Settings([
                                'AudioDuration' => M3u8AudioDuration::DEFAULT_CODEC_DURATION,
                                'AudioFramesPerPes' => 1337,
                                'AudioPids' => [1337],
                                'DataPTSControl' => M3u8DataPtsControl::AUTO,
                                'MaxPcrInterval' => 1337,
                                'NielsenId3' => M3u8NielsenId3::NONE,
                                'PatInterval' => 1337,
                                'PcrControl' => M3u8PcrControl::CONFIGURED_PCR_PERIOD,
                                'PcrPid' => 1337,
                                'PmtInterval' => 1337,
                                'PmtPid' => 1337,
                                'PrivateMetadataPid' => 1337,
                                'ProgramNumber' => 1337,
                                'Scte35Pid' => 1337,
                                'Scte35Source' => M3u8Scte35Source::NONE,
                                'TimedMetadata' => TimedMetadata::NONE,
                                'TimedMetadataPid' => 1337,
                                'TransportStreamId' => 1337,
                                'VideoPid' => 1337,
                            ]),
                            'MovSettings' => new MovSettings([
                                'ClapAtom' => MovClapAtom::EXCLUDE,
                                'CslgAtom' => MovCslgAtom::EXCLUDE,
                                'Mpeg2FourCCControl' => MovMpeg2FourCCControl::MPEG,
                                'PaddingControl' => MovPaddingControl::NONE,
                                'Reference' => MovReference::EXTERNAL,
                            ]),
                            'Mp4Settings' => new Mp4Settings([
                                'AudioDuration' => CmfcAudioDuration::DEFAULT_CODEC_DURATION,
                                'CslgAtom' => Mp4CslgAtom::EXCLUDE,
                                'CttsVersion' => 1337,
                                'FreeSpaceBox' => Mp4FreeSpaceBox::EXCLUDE,
                                'MoovPlacement' => Mp4MoovPlacement::NORMAL,
                                'Mp4MajorBrand' => 'change me',
                            ]),
                            'MpdSettings' => new MpdSettings([
                                'AccessibilityCaptionHints' => MpdAccessibilityCaptionHints::EXCLUDE,
                                'AudioDuration' => MpdAudioDuration::DEFAULT_CODEC_DURATION,
                                'CaptionContainerType' => MpdCaptionContainerType::FRAGMENTED_MP4,
                                'KlvMetadata' => MpdKlvMetadata::NONE,
                                'ManifestMetadataSignaling' => MpdManifestMetadataSignaling::DISABLED,
                                'Scte35Esam' => MpdScte35Esam::NONE,
                                'Scte35Source' => MpdScte35Source::NONE,
                                'TimedMetadata' => MpdTimedMetadata::NONE,
                                'TimedMetadataBoxVersion' => MpdTimedMetadataBoxVersion::VERSION_0,
                                'TimedMetadataSchemeIdUri' => 'change me',
                                'TimedMetadataValue' => 'change me',
                            ]),
                            'MxfSettings' => new MxfSettings([
                                'AfdSignaling' => MxfAfdSignaling::NO_COPY,
                                'Profile' => MxfProfile::D_10,
                                'XavcProfileSettings' => new MxfXavcProfileSettings([
                                    'DurationMode' => MxfXavcDurationMode::ALLOW_ANY_DURATION,
                                    'MaxAncDataSize' => 1337,
                                ]),
                            ]),
                        ]),
                        'Extension' => 'change me',
                        'NameModifier' => 'change me',
                        'OutputSettings' => new OutputSettings([
                            'HlsSettings' => new HlsSettings([
                                'AudioGroupId' => 'change me',
                                'AudioOnlyContainer' => HlsAudioOnlyContainer::AUTOMATIC,
                                'AudioRenditionSets' => 'change me',
                                'AudioTrackType' => HlsAudioTrackType::ALTERNATE_AUDIO_AUTO_SELECT,
                                'DescriptiveVideoServiceFlag' => HlsDescriptiveVideoServiceFlag::FLAG,
                                'IFrameOnlyManifest' => HlsIFrameOnlyManifest::EXCLUDE,
                                'SegmentModifier' => 'change me',
                            ]),
                        ]),
                        'Preset' => 'change me',
                        'VideoDescription' => new VideoDescription([
                            'AfdSignaling' => AfdSignaling::NONE,
                            'AntiAlias' => AntiAlias::DISABLED,
                            'CodecSettings' => new VideoCodecSettings([
                                'Av1Settings' => new Av1Settings([
                                    'AdaptiveQuantization' => Av1AdaptiveQuantization::HIGH,
                                    'BitDepth' => Av1BitDepth::BIT_8,
                                    'FramerateControl' => Av1FramerateControl::SPECIFIED,
                                    'FramerateConversionAlgorithm' => Av1FramerateConversionAlgorithm::INTERPOLATE,
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopSize' => 1337,
                                    'MaxBitrate' => 1337,
                                    'NumberBFramesBetweenReferenceFrames' => 1337,
                                    'QvbrSettings' => new Av1QvbrSettings([
                                        'QvbrQualityLevel' => 1337,
                                        'QvbrQualityLevelFineTune' => 1337,
                                    ]),
                                    'RateControlMode' => Av1RateControlMode::QVBR,
                                    'Slices' => 1337,
                                    'SpatialAdaptiveQuantization' => Av1SpatialAdaptiveQuantization::DISABLED,
                                ]),
                                'AvcIntraSettings' => new AvcIntraSettings([
                                    'AvcIntraClass' => AvcIntraClass::CLASS_4K_2K,
                                    'AvcIntraUhdSettings' => new AvcIntraUhdSettings([
                                        'QualityTuningLevel' => AvcIntraUhdQualityTuningLevel::SINGLE_PASS,
                                    ]),
                                    'FramerateControl' => AvcIntraFramerateControl::SPECIFIED,
                                    'FramerateConversionAlgorithm' => AvcIntraFramerateConversionAlgorithm::INTERPOLATE,
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'InterlaceMode' => AvcIntraInterlaceMode::PROGRESSIVE,
                                    'ScanTypeConversionMode' => AvcIntraScanTypeConversionMode::INTERLACED_OPTIMIZE,
                                    'SlowPal' => AvcIntraSlowPal::DISABLED,
                                    'Telecine' => AvcIntraTelecine::NONE,
                                ]),
                                'Codec' => VideoCodec::AV1,
                                'FrameCaptureSettings' => new FrameCaptureSettings([
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'MaxCaptures' => 1337,
                                    'Quality' => 1337,
                                ]),
                                'H264Settings' => new H264Settings([
                                    'AdaptiveQuantization' => H264AdaptiveQuantization::HIGH,
                                    'BandwidthReductionFilter' => new BandwidthReductionFilter([
                                        'Sharpening' => BandwidthReductionFilterSharpening::HIGH,
                                        'Strength' => BandwidthReductionFilterStrength::HIGH,
                                    ]),
                                    'Bitrate' => 1337,
                                    'CodecLevel' => H264CodecLevel::AUTO,
                                    'CodecProfile' => H264CodecProfile::HIGH,
                                    'DynamicSubGop' => H264DynamicSubGop::ADAPTIVE,
                                    'EntropyEncoding' => H264EntropyEncoding::CABAC,
                                    'FieldEncoding' => H264FieldEncoding::FORCE_FIELD,
                                    'FlickerAdaptiveQuantization' => H264FlickerAdaptiveQuantization::DISABLED,
                                    'FramerateControl' => H264FramerateControl::SPECIFIED,
                                    'FramerateConversionAlgorithm' => H264FramerateConversionAlgorithm::INTERPOLATE,
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopBReference' => H264GopBReference::DISABLED,
                                    'GopClosedCadence' => 1337,
                                    'GopSize' => 1337,
                                    'GopSizeUnits' => H264GopSizeUnits::AUTO,
                                    'HrdBufferFinalFillPercentage' => 1337,
                                    'HrdBufferInitialFillPercentage' => 1337,
                                    'HrdBufferSize' => 1337,
                                    'InterlaceMode' => H264InterlaceMode::PROGRESSIVE,
                                    'MaxBitrate' => 1337,
                                    'MinIInterval' => 1337,
                                    'NumberBFramesBetweenReferenceFrames' => 1337,
                                    'NumberReferenceFrames' => 1337,
                                    'ParControl' => H264ParControl::SPECIFIED,
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'QualityTuningLevel' => H264QualityTuningLevel::SINGLE_PASS,
                                    'QvbrSettings' => new H264QvbrSettings([
                                        'MaxAverageBitrate' => 1337,
                                        'QvbrQualityLevel' => 1337,
                                        'QvbrQualityLevelFineTune' => 1337,
                                    ]),
                                    'RateControlMode' => H264RateControlMode::QVBR,
                                    'RepeatPps' => H264RepeatPps::DISABLED,
                                    'ScanTypeConversionMode' => H264ScanTypeConversionMode::INTERLACED_OPTIMIZE,
                                    'SceneChangeDetect' => H264SceneChangeDetect::DISABLED,
                                    'Slices' => 1337,
                                    'SlowPal' => H264SlowPal::DISABLED,
                                    'Softness' => 1337,
                                    'SpatialAdaptiveQuantization' => H264SpatialAdaptiveQuantization::DISABLED,
                                    'Syntax' => H264Syntax::DEFAULT,
                                    'Telecine' => H264Telecine::NONE,
                                    'TemporalAdaptiveQuantization' => H264TemporalAdaptiveQuantization::DISABLED,
                                    'UnregisteredSeiTimecode' => H264UnregisteredSeiTimecode::DISABLED,
                                ]),
                                'H265Settings' => new H265Settings([
                                    'AdaptiveQuantization' => H265AdaptiveQuantization::AUTO,
                                    'AlternateTransferFunctionSei' => H265AlternateTransferFunctionSei::DISABLED,
                                    'Bitrate' => 1337,
                                    'CodecLevel' => H265CodecLevel::AUTO,
                                    'CodecProfile' => H265CodecProfile::MAIN10_HIGH,
                                    'DynamicSubGop' => H265DynamicSubGop::ADAPTIVE,
                                    'FlickerAdaptiveQuantization' => H265FlickerAdaptiveQuantization::DISABLED,
                                    'FramerateControl' => H265FramerateControl::SPECIFIED,
                                    'FramerateConversionAlgorithm' => H265FramerateConversionAlgorithm::INTERPOLATE,
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopBReference' => H265GopBReference::DISABLED,
                                    'GopClosedCadence' => 1337,
                                    'GopSize' => 1337,
                                    'GopSizeUnits' => H265GopSizeUnits::AUTO,
                                    'HrdBufferFinalFillPercentage' => 1337,
                                    'HrdBufferInitialFillPercentage' => 1337,
                                    'HrdBufferSize' => 1337,
                                    'InterlaceMode' => H265InterlaceMode::PROGRESSIVE,
                                    'MaxBitrate' => 1337,
                                    'MinIInterval' => 1337,
                                    'NumberBFramesBetweenReferenceFrames' => 1337,
                                    'NumberReferenceFrames' => 1337,
                                    'ParControl' => H265ParControl::SPECIFIED,
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'QualityTuningLevel' => H265QualityTuningLevel::SINGLE_PASS,
                                    'QvbrSettings' => new H265QvbrSettings([
                                        'MaxAverageBitrate' => 1337,
                                        'QvbrQualityLevel' => 1337,
                                        'QvbrQualityLevelFineTune' => 1337,
                                    ]),
                                    'RateControlMode' => H265RateControlMode::QVBR,
                                    'SampleAdaptiveOffsetFilterMode' => H265SampleAdaptiveOffsetFilterMode::ADAPTIVE,
                                    'ScanTypeConversionMode' => H265ScanTypeConversionMode::INTERLACED_OPTIMIZE,
                                    'SceneChangeDetect' => H265SceneChangeDetect::DISABLED,
                                    'Slices' => 1337,
                                    'SlowPal' => H265SlowPal::DISABLED,
                                    'SpatialAdaptiveQuantization' => H265SpatialAdaptiveQuantization::DISABLED,
                                    'Telecine' => H265Telecine::NONE,
                                    'TemporalAdaptiveQuantization' => H265TemporalAdaptiveQuantization::DISABLED,
                                    'TemporalIds' => H265TemporalIds::DISABLED,
                                    'Tiles' => H265Tiles::DISABLED,
                                    'UnregisteredSeiTimecode' => H265UnregisteredSeiTimecode::DISABLED,
                                    'WriteMp4PackagingType' => H265WriteMp4PackagingType::HEV1,
                                ]),
                                'Mpeg2Settings' => new Mpeg2Settings([
                                    'AdaptiveQuantization' => Mpeg2AdaptiveQuantization::HIGH,
                                    'Bitrate' => 1337,
                                    'CodecLevel' => Mpeg2CodecLevel::HIGH,
                                    'CodecProfile' => Mpeg2CodecProfile::PROFILE_422,
                                    'DynamicSubGop' => Mpeg2DynamicSubGop::ADAPTIVE,
                                    'FramerateControl' => Mpeg2FramerateControl::SPECIFIED,
                                    'FramerateConversionAlgorithm' => Mpeg2FramerateConversionAlgorithm::INTERPOLATE,
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopClosedCadence' => 1337,
                                    'GopSize' => 1337,
                                    'GopSizeUnits' => Mpeg2GopSizeUnits::SECONDS,
                                    'HrdBufferFinalFillPercentage' => 1337,
                                    'HrdBufferInitialFillPercentage' => 1337,
                                    'HrdBufferSize' => 1337,
                                    'InterlaceMode' => Mpeg2InterlaceMode::PROGRESSIVE,
                                    'IntraDcPrecision' => Mpeg2IntraDcPrecision::INTRA_DC_PRECISION_8,
                                    'MaxBitrate' => 1337,
                                    'MinIInterval' => 1337,
                                    'NumberBFramesBetweenReferenceFrames' => 1337,
                                    'ParControl' => Mpeg2ParControl::SPECIFIED,
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'QualityTuningLevel' => Mpeg2QualityTuningLevel::SINGLE_PASS,
                                    'RateControlMode' => Mpeg2RateControlMode::CBR,
                                    'ScanTypeConversionMode' => Mpeg2ScanTypeConversionMode::INTERLACED_OPTIMIZE,
                                    'SceneChangeDetect' => Mpeg2SceneChangeDetect::DISABLED,
                                    'SlowPal' => Mpeg2SlowPal::DISABLED,
                                    'Softness' => 1337,
                                    'SpatialAdaptiveQuantization' => Mpeg2SpatialAdaptiveQuantization::DISABLED,
                                    'Syntax' => Mpeg2Syntax::DEFAULT,
                                    'Telecine' => Mpeg2Telecine::NONE,
                                    'TemporalAdaptiveQuantization' => Mpeg2TemporalAdaptiveQuantization::DISABLED,
                                ]),
                                'ProresSettings' => new ProresSettings([
                                    'ChromaSampling' => ProresChromaSampling::PRESERVE_444_SAMPLING,
                                    'CodecProfile' => ProresCodecProfile::APPLE_PRORES_422,
                                    'FramerateControl' => ProresFramerateControl::SPECIFIED,
                                    'FramerateConversionAlgorithm' => ProresFramerateConversionAlgorithm::INTERPOLATE,
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'InterlaceMode' => ProresInterlaceMode::PROGRESSIVE,
                                    'ParControl' => ProresParControl::SPECIFIED,
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'ScanTypeConversionMode' => ProresScanTypeConversionMode::INTERLACED_OPTIMIZE,
                                    'SlowPal' => ProresSlowPal::DISABLED,
                                    'Telecine' => ProresTelecine::NONE,
                                ]),
                                'Vc3Settings' => new Vc3Settings([
                                    'FramerateControl' => Vc3FramerateControl::SPECIFIED,
                                    'FramerateConversionAlgorithm' => Vc3FramerateConversionAlgorithm::INTERPOLATE,
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'InterlaceMode' => Vc3InterlaceMode::INTERLACED,
                                    'ScanTypeConversionMode' => Vc3ScanTypeConversionMode::INTERLACED,
                                    'SlowPal' => Vc3SlowPal::DISABLED,
                                    'Telecine' => Vc3Telecine::NONE,
                                    'Vc3Class' => Vc3Class::CLASS_145_8BIT,
                                ]),
                                'Vp8Settings' => new Vp8Settings([
                                    'Bitrate' => 1337,
                                    'FramerateControl' => Vp8FramerateControl::SPECIFIED,
                                    'FramerateConversionAlgorithm' => Vp8FramerateConversionAlgorithm::INTERPOLATE,
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopSize' => 1337,
                                    'HrdBufferSize' => 1337,
                                    'MaxBitrate' => 1337,
                                    'ParControl' => Vp8ParControl::SPECIFIED,
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'QualityTuningLevel' => Vp8QualityTuningLevel::MULTI_PASS,
                                    'RateControlMode' => Vp8RateControlMode::VBR,
                                ]),
                                'Vp9Settings' => new Vp9Settings([
                                    'Bitrate' => 1337,
                                    'FramerateControl' => Vp9FramerateControl::SPECIFIED,
                                    'FramerateConversionAlgorithm' => Vp9FramerateConversionAlgorithm::INTERPOLATE,
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopSize' => 1337,
                                    'HrdBufferSize' => 1337,
                                    'MaxBitrate' => 1337,
                                    'ParControl' => Vp9ParControl::SPECIFIED,
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'QualityTuningLevel' => Vp9QualityTuningLevel::MULTI_PASS,
                                    'RateControlMode' => Vp9RateControlMode::VBR,
                                ]),
                                'XavcSettings' => new XavcSettings([
                                    'AdaptiveQuantization' => XavcAdaptiveQuantization::HIGH,
                                    'EntropyEncoding' => XavcEntropyEncoding::AUTO,
                                    'FramerateControl' => XavcFramerateControl::SPECIFIED,
                                    'FramerateConversionAlgorithm' => XavcFramerateConversionAlgorithm::INTERPOLATE,
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'Profile' => XavcProfile::XAVC_4K,
                                    'SlowPal' => XavcSlowPal::DISABLED,
                                    'Softness' => 1337,
                                    'SpatialAdaptiveQuantization' => XavcSpatialAdaptiveQuantization::DISABLED,
                                    'TemporalAdaptiveQuantization' => XavcTemporalAdaptiveQuantization::DISABLED,
                                    'Xavc4kIntraCbgProfileSettings' => new Xavc4kIntraCbgProfileSettings([
                                        'XavcClass' => Xavc4kIntraCbgProfileClass::CLASS_100,
                                    ]),
                                    'Xavc4kIntraVbrProfileSettings' => new Xavc4kIntraVbrProfileSettings([
                                        'XavcClass' => Xavc4kIntraVbrProfileClass::CLASS_100,
                                    ]),
                                    'Xavc4kProfileSettings' => new Xavc4kProfileSettings([
                                        'BitrateClass' => Xavc4kProfileBitrateClass::BITRATE_CLASS_100,
                                        'CodecProfile' => Xavc4kProfileCodecProfile::HIGH,
                                        'FlickerAdaptiveQuantization' => XavcFlickerAdaptiveQuantization::DISABLED,
                                        'GopBReference' => XavcGopBReference::DISABLED,
                                        'GopClosedCadence' => 1337,
                                        'HrdBufferSize' => 1337,
                                        'QualityTuningLevel' => Xavc4kProfileQualityTuningLevel::SINGLE_PASS,
                                        'Slices' => 1337,
                                    ]),
                                    'XavcHdIntraCbgProfileSettings' => new XavcHdIntraCbgProfileSettings([
                                        'XavcClass' => XavcHdIntraCbgProfileClass::CLASS_100,
                                    ]),
                                    'XavcHdProfileSettings' => new XavcHdProfileSettings([
                                        'BitrateClass' => XavcHdProfileBitrateClass::BITRATE_CLASS_25,
                                        'FlickerAdaptiveQuantization' => XavcFlickerAdaptiveQuantization::DISABLED,
                                        'GopBReference' => XavcGopBReference::DISABLED,
                                        'GopClosedCadence' => 1337,
                                        'HrdBufferSize' => 1337,
                                        'InterlaceMode' => XavcInterlaceMode::PROGRESSIVE,
                                        'QualityTuningLevel' => XavcHdProfileQualityTuningLevel::SINGLE_PASS,
                                        'Slices' => 1337,
                                        'Telecine' => XavcHdProfileTelecine::NONE,
                                    ]),
                                ]),
                            ]),
                            'ColorMetadata' => ColorMetadata::IGNORE,
                            'Crop' => new Rectangle([
                                'Height' => 1337,
                                'Width' => 1337,
                                'X' => 1337,
                                'Y' => 1337,
                            ]),
                            'DropFrameTimecode' => DropFrameTimecode::DISABLED,
                            'FixedAfd' => 1337,
                            'Height' => 1337,
                            'Position' => new Rectangle([
                                'Height' => 1337,
                                'Width' => 1337,
                                'X' => 1337,
                                'Y' => 1337,
                            ]),
                            'RespondToAfd' => RespondToAfd::NONE,
                            'ScalingBehavior' => ScalingBehavior::DEFAULT,
                            'Sharpness' => 1337,
                            'TimecodeInsertion' => VideoTimecodeInsertion::DISABLED,
                            'VideoPreprocessors' => new VideoPreprocessor([
                                'ColorCorrector' => new ColorCorrector([
                                    'Brightness' => 1337,
                                    'ClipLimits' => new ClipLimits([
                                        'MaximumRGBTolerance' => 1337,
                                        'MaximumYUV' => 1337,
                                        'MinimumRGBTolerance' => 1337,
                                        'MinimumYUV' => 1337,
                                    ]),
                                    'ColorSpaceConversion' => ColorSpaceConversion::NONE,
                                    'Contrast' => 1337,
                                    'Hdr10Metadata' => new Hdr10Metadata([
                                        'BluePrimaryX' => 1337,
                                        'BluePrimaryY' => 1337,
                                        'GreenPrimaryX' => 1337,
                                        'GreenPrimaryY' => 1337,
                                        'MaxContentLightLevel' => 1337,
                                        'MaxFrameAverageLightLevel' => 1337,
                                        'MaxLuminance' => 1337,
                                        'MinLuminance' => 1337,
                                        'RedPrimaryX' => 1337,
                                        'RedPrimaryY' => 1337,
                                        'WhitePointX' => 1337,
                                        'WhitePointY' => 1337,
                                    ]),
                                    'HdrToSdrToneMapper' => HDRToSDRToneMapper::PRESERVE_DETAILS,
                                    'Hue' => 1337,
                                    'SampleRangeConversion' => SampleRangeConversion::NONE,
                                    'Saturation' => 1337,
                                    'SdrReferenceWhiteLevel' => 1337,
                                ]),
                                'Deinterlacer' => new Deinterlacer([
                                    'Algorithm' => DeinterlaceAlgorithm::INTERPOLATE,
                                    'Control' => DeinterlacerControl::NORMAL,
                                    'Mode' => DeinterlacerMode::DEINTERLACE,
                                ]),
                                'DolbyVision' => new DolbyVision([
                                    'L6Metadata' => new DolbyVisionLevel6Metadata([
                                        'MaxCll' => 1337,
                                        'MaxFall' => 1337,
                                    ]),
                                    'L6Mode' => DolbyVisionLevel6Mode::PASSTHROUGH,
                                    'Mapping' => DolbyVisionMapping::HDR10_NOMAP,
                                    'Profile' => DolbyVisionProfile::PROFILE_5,
                                ]),
                                'Hdr10Plus' => new Hdr10Plus([
                                    'MasteringMonitorNits' => 1337,
                                    'TargetMonitorNits' => 1337,
                                ]),
                                'ImageInserter' => new ImageInserter([
                                    'InsertableImages' => [new InsertableImage([
                                        'Duration' => 1337,
                                        'FadeIn' => 1337,
                                        'FadeOut' => 1337,
                                        'Height' => 1337,
                                        'ImageInserterInput' => 'change me',
                                        'ImageX' => 1337,
                                        'ImageY' => 1337,
                                        'Layer' => 1337,
                                        'Opacity' => 1337,
                                        'StartTime' => 'change me',
                                        'Width' => 1337,
                                    ])],
                                    'SdrReferenceWhiteLevel' => 1337,
                                ]),
                                'NoiseReducer' => new NoiseReducer([
                                    'Filter' => NoiseReducerFilter::BILATERAL,
                                    'FilterSettings' => new NoiseReducerFilterSettings([
                                        'Strength' => 1337,
                                    ]),
                                    'SpatialFilterSettings' => new NoiseReducerSpatialFilterSettings([
                                        'PostFilterSharpenStrength' => 1337,
                                        'Speed' => 1337,
                                        'Strength' => 1337,
                                    ]),
                                    'TemporalFilterSettings' => new NoiseReducerTemporalFilterSettings([
                                        'AggressiveMode' => 1337,
                                        'PostTemporalSharpening' => NoiseFilterPostTemporalSharpening::DISABLED,
                                        'PostTemporalSharpeningStrength' => NoiseFilterPostTemporalSharpeningStrength::HIGH,
                                        'Speed' => 1337,
                                        'Strength' => 1337,
                                    ]),
                                ]),
                                'PartnerWatermarking' => new PartnerWatermarking([
                                    'NexguardFileMarkerSettings' => new NexGuardFileMarkerSettings([
                                        'License' => 'change me',
                                        'Payload' => 1337,
                                        'Preset' => 'change me',
                                        'Strength' => WatermarkingStrength::DEFAULT,
                                    ]),
                                ]),
                                'TimecodeBurnin' => new TimecodeBurnin([
                                    'FontSize' => 1337,
                                    'Position' => TimecodeBurninPosition::BOTTOM_CENTER,
                                    'Prefix' => 'change me',
                                ]),
                            ]),
                            'Width' => 1337,
                        ]),
                    ])],
                ])],
                'TimecodeConfig' => new TimecodeConfig([
                    'Anchor' => 'change me',
                    'Source' => TimecodeSource::EMBEDDED,
                    'Start' => 'change me',
                    'TimestampOffset' => 'change me',
                ]),
                'TimedMetadataInsertion' => new TimedMetadataInsertion([
                    'Id3Insertions' => [new Id3Insertion([
                        'Id3' => 'change me',
                        'Timecode' => 'change me',
                    ])],
                ]),
            ]),
            'SimulateReservedQueue' => SimulateReservedQueue::DISABLED,
            'StatusUpdateInterval' => StatusUpdateInterval::SECONDS_10,
            'Tags' => ['change me' => 'change me'],
            'UserMetadata' => ['change me' => 'change me'],
        ]);

        // see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_CreateJob.html
        $expected = '
            POST /2017-08-29/jobs HTTP/1.0
            Content-Type: application/json

            {
                "accelerationSettings": {
                    "mode": "ENABLED"
                },
                "billingTagsSource": "JOB",
                "clientRequestToken": "change me",
                "hopDestinations": [
                    {
                        "priority": 1337,
                        "queue": "change me",
                        "waitMinutes": 1337
                    }
                ],
                "jobTemplate": "change me",
                "priority": 1337,
                "queue": "change me",
                "role": "change me",
                "settings": {
                    "adAvailOffset": 1337,
                    "availBlanking": {
                        "availBlankingImage": "change me"
                    },
                    "esam": {
                        "manifestConfirmConditionNotification": {
                            "mccXml": "change me"
                        },
                        "responseSignalPreroll": 1337,
                        "signalProcessingNotification": {
                            "sccXml": "change me"
                        }
                    },
                    "extendedDataServices": {
                        "copyProtectionAction": "PASSTHROUGH",
                        "vchipAction": "PASSTHROUGH"
                    },
                    "inputs": [
                        {
                            "advancedInputFilter": "ENABLED",
                            "advancedInputFilterSettings": {
                                "addTexture": "DISABLED",
                                "sharpening": "HIGH"
                            },
                            "audioSelectorGroups": {
                                "change me": {
                                    "audioSelectorNames": [
                                        "change me"
                                    ]
                                }
                            },
                            "audioSelectors": {
                                "change me": {
                                    "audioDurationCorrection": "AUTO",
                                    "customLanguageCode": "change me",
                                    "defaultSelection": "DEFAULT",
                                    "externalAudioFileInput": "change me",
                                    "hlsRenditionGroupSettings": {
                                        "renditionGroupId": "change me",
                                        "renditionLanguageCode": "ENG",
                                        "renditionName": "change me"
                                    },
                                    "languageCode": "ENG",
                                    "offset": 1337,
                                    "pids": [
                                        1337
                                    ],
                                    "programSelection": 1337,
                                    "remixSettings": {
                                        "channelMapping": {
                                            "outputChannels": [
                                                {
                                                    "inputChannels": [
                                                        1337
                                                    ],
                                                    "inputChannelsFineTune": [
                                                        1337
                                                    ]
                                                }
                                            ]
                                        },
                                        "channelsIn": 1337,
                                        "channelsOut": 1337
                                    },
                                    "selectorType": "HLS_RENDITION_GROUP",
                                    "tracks": [
                                        1337
                                    ]
                                }
                            },
                            "captionSelectors": {
                                "change me": {
                                    "customLanguageCode": "change me",
                                    "languageCode": "ENG",
                                    "sourceSettings": {
                                        "ancillarySourceSettings": {
                                            "convert608To708": "UPCONVERT",
                                            "sourceAncillaryChannelNumber": 1337,
                                            "terminateCaptions": "END_OF_INPUT"
                                        },
                                        "dvbSubSourceSettings": {
                                            "pid": 1337
                                        },
                                        "embeddedSourceSettings": {
                                            "convert608To708": "UPCONVERT",
                                            "source608ChannelNumber": 1337,
                                            "source608TrackNumber": 1337,
                                            "terminateCaptions": "END_OF_INPUT"
                                        },
                                        "fileSourceSettings": {
                                            "convert608To708": "UPCONVERT",
                                            "convertPaintToPop": "DISABLED",
                                            "framerate": {
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337
                                            },
                                            "sourceFile": "change me",
                                            "timeDelta": 1337,
                                            "timeDeltaUnits": "MILLISECONDS"
                                        },
                                        "sourceType": "ANCILLARY",
                                        "teletextSourceSettings": {
                                            "pageNumber": "change me"
                                        },
                                        "trackSourceSettings": {
                                            "trackNumber": 1337
                                        },
                                        "webvttHlsSourceSettings": {
                                            "renditionGroupId": "change me",
                                            "renditionLanguageCode": "ENG",
                                            "renditionName": "change me"
                                        }
                                    }
                                }
                            },
                            "crop": {
                                "height": 1337,
                                "width": 1337,
                                "x": 1337,
                                "y": 1337
                            },
                            "deblockFilter": "DISABLED",
                            "decryptionSettings": {
                                "decryptionMode": "AES_CBC",
                                "encryptedDecryptionKey": "change me",
                                "initializationVector": "change me",
                                "kmsKeyRegion": "change me"
                            },
                            "denoiseFilter": "DISABLED",
                            "dolbyVisionMetadataXml": "change me",
                            "fileInput": "change me",
                            "filterEnable": "AUTO",
                            "filterStrength": 1337,
                            "imageInserter": {
                                "insertableImages": [
                                    {
                                        "duration": 1337,
                                        "fadeIn": 1337,
                                        "fadeOut": 1337,
                                        "height": 1337,
                                        "imageInserterInput": "change me",
                                        "imageX": 1337,
                                        "imageY": 1337,
                                        "layer": 1337,
                                        "opacity": 1337,
                                        "startTime": "change me",
                                        "width": 1337
                                    }
                                ],
                                "sdrReferenceWhiteLevel": 1337
                            },
                            "inputClippings": [
                                {
                                    "endTimecode": "change me",
                                    "startTimecode": "change me"
                                }
                            ],
                            "inputScanType": "AUTO",
                            "position": {
                                "height": 1337,
                                "width": 1337,
                                "x": 1337,
                                "y": 1337
                            },
                            "programNumber": 1337,
                            "psiControl": "IGNORE_PSI",
                            "supplementalImps": [
                                "change me"
                            ],
                            "timecodeSource": "EMBEDDED",
                            "timecodeStart": "change me",
                            "videoGenerator": {
                                "duration": 1337
                            },
                            "videoSelector": {
                                "alphaBehavior": "DISCARD",
                                "colorSpace": "HDR10",
                                "colorSpaceUsage": "FALLBACK",
                                "embeddedTimecodeOverride": "NONE",
                                "hdr10Metadata": {
                                    "bluePrimaryX": 1337,
                                    "bluePrimaryY": 1337,
                                    "greenPrimaryX": 1337,
                                    "greenPrimaryY": 1337,
                                    "maxContentLightLevel": 1337,
                                    "maxFrameAverageLightLevel": 1337,
                                    "maxLuminance": 1337,
                                    "minLuminance": 1337,
                                    "redPrimaryX": 1337,
                                    "redPrimaryY": 1337,
                                    "whitePointX": 1337,
                                    "whitePointY": 1337
                                },
                                "padVideo": "BLACK",
                                "pid": 1337,
                                "programNumber": 1337,
                                "rotate": "AUTO",
                                "sampleRange": "FULL_RANGE"
                            }
                        }
                    ],
                    "kantarWatermark": {
                        "channelName": "change me",
                        "contentReference": "change me",
                        "credentialsSecretName": "change me",
                        "fileOffset": 1337,
                        "kantarLicenseId": 1337,
                        "kantarServerUrl": "change me",
                        "logDestination": "change me",
                        "metadata3": "change me",
                        "metadata4": "change me",
                        "metadata5": "change me",
                        "metadata6": "change me",
                        "metadata7": "change me",
                        "metadata8": "change me"
                    },
                    "motionImageInserter": {
                        "framerate": {
                            "framerateDenominator": 1337,
                            "framerateNumerator": 1337
                        },
                        "input": "change me",
                        "insertionMode": "PNG",
                        "offset": {
                            "imageX": 1337,
                            "imageY": 1337
                        },
                        "playback": "ONCE",
                        "startTime": "change me"
                    },
                    "nielsenConfiguration": {
                        "breakoutCode": 1337,
                        "distributorId": "change me"
                    },
                    "nielsenNonLinearWatermark": {
                        "activeWatermarkProcess": "CBET",
                        "adiFilename": "change me",
                        "assetId": "change me",
                        "assetName": "change me",
                        "cbetSourceId": "change me",
                        "episodeId": "change me",
                        "metadataDestination": "change me",
                        "sourceId": 1337,
                        "sourceWatermarkStatus": "CLEAN",
                        "ticServerUrl": "change me",
                        "uniqueTicPerAudioTrack": "SAME_TICS_PER_TRACK"
                    },
                    "outputGroups": [
                        {
                            "automatedEncodingSettings": {
                                "abrSettings": {
                                    "maxAbrBitrate": 1337,
                                    "maxRenditions": 1337,
                                    "minAbrBitrate": 1337,
                                    "rules": [
                                        {
                                            "allowedRenditions": [
                                                {
                                                    "height": 1337,
                                                    "required": "ENABLED",
                                                    "width": 1337
                                                }
                                            ],
                                            "forceIncludeRenditions": [
                                                {
                                                    "height": 1337,
                                                    "width": 1337
                                                }
                                            ],
                                            "minBottomRenditionSize": {
                                                "height": 1337,
                                                "width": 1337
                                            },
                                            "minTopRenditionSize": {
                                                "height": 1337,
                                                "width": 1337
                                            },
                                            "type": "ALLOWED_RENDITIONS"
                                        }
                                    ]
                                }
                            },
                            "customName": "change me",
                            "name": "change me",
                            "outputGroupSettings": {
                                "cmafGroupSettings": {
                                    "additionalManifests": [
                                        {
                                            "manifestNameModifier": "change me",
                                            "selectedOutputs": [
                                                "change me"
                                            ]
                                        }
                                    ],
                                    "baseUrl": "change me",
                                    "clientCache": "DISABLED",
                                    "codecSpecification": "RFC_6381",
                                    "dashManifestStyle": "BASIC",
                                    "destination": "change me",
                                    "destinationSettings": {
                                        "s3Settings": {
                                            "accessControl": {
                                                "cannedAcl": "PUBLIC_READ"
                                            },
                                            "encryption": {
                                                "encryptionType": "SERVER_SIDE_ENCRYPTION_S3",
                                                "kmsEncryptionContext": "change me",
                                                "kmsKeyArn": "change me"
                                            }
                                        }
                                    },
                                    "encryption": {
                                        "constantInitializationVector": "change me",
                                        "encryptionMethod": "AES_CTR",
                                        "initializationVectorInManifest": "EXCLUDE",
                                        "spekeKeyProvider": {
                                            "certificateArn": "change me",
                                            "dashSignaledSystemIds": [
                                                "change me"
                                            ],
                                            "hlsSignaledSystemIds": [
                                                "change me"
                                            ],
                                            "resourceId": "change me",
                                            "url": "change me"
                                        },
                                        "staticKeyProvider": {
                                            "keyFormat": "change me",
                                            "keyFormatVersions": "change me",
                                            "staticKeyValue": "change me",
                                            "url": "change me"
                                        },
                                        "type": "STATIC_KEY"
                                    },
                                    "fragmentLength": 1337,
                                    "imageBasedTrickPlay": "NONE",
                                    "imageBasedTrickPlaySettings": {
                                        "intervalCadence": "FOLLOW_IFRAME",
                                        "thumbnailHeight": 1337,
                                        "thumbnailInterval": 1337,
                                        "thumbnailWidth": 1337,
                                        "tileHeight": 1337,
                                        "tileWidth": 1337
                                    },
                                    "manifestCompression": "GZIP",
                                    "manifestDurationFormat": "INTEGER",
                                    "minBufferTime": 1337,
                                    "minFinalSegmentLength": 1337,
                                    "mpdManifestBandwidthType": "MAX",
                                    "mpdProfile": "MAIN_PROFILE",
                                    "ptsOffsetHandlingForBFrames": "MATCH_INITIAL_PTS",
                                    "segmentControl": "SINGLE_FILE",
                                    "segmentLength": 1337,
                                    "segmentLengthControl": "EXACT",
                                    "streamInfResolution": "EXCLUDE",
                                    "targetDurationCompatibilityMode": "SPEC_COMPLIANT",
                                    "videoCompositionOffsets": "SIGNED",
                                    "writeDashManifest": "DISABLED",
                                    "writeHlsManifest": "DISABLED",
                                    "writeSegmentTimelineInRepresentation": "DISABLED"
                                },
                                "dashIsoGroupSettings": {
                                    "additionalManifests": [
                                        {
                                            "manifestNameModifier": "change me",
                                            "selectedOutputs": [
                                                "change me"
                                            ]
                                        }
                                    ],
                                    "audioChannelConfigSchemeIdUri": "DOLBY_CHANNEL_CONFIGURATION",
                                    "baseUrl": "change me",
                                    "dashManifestStyle": "BASIC",
                                    "destination": "change me",
                                    "destinationSettings": {
                                        "s3Settings": {
                                            "accessControl": {
                                                "cannedAcl": "PUBLIC_READ"
                                            },
                                            "encryption": {
                                                "encryptionType": "SERVER_SIDE_ENCRYPTION_S3",
                                                "kmsEncryptionContext": "change me",
                                                "kmsKeyArn": "change me"
                                            }
                                        }
                                    },
                                    "encryption": {
                                        "playbackDeviceCompatibility": "CENC_V1",
                                        "spekeKeyProvider": {
                                            "certificateArn": "change me",
                                            "resourceId": "change me",
                                            "systemIds": [
                                                "change me"
                                            ],
                                            "url": "change me"
                                        }
                                    },
                                    "fragmentLength": 1337,
                                    "hbbtvCompliance": "NONE",
                                    "imageBasedTrickPlay": "NONE",
                                    "imageBasedTrickPlaySettings": {
                                        "intervalCadence": "FOLLOW_IFRAME",
                                        "thumbnailHeight": 1337,
                                        "thumbnailInterval": 1337,
                                        "thumbnailWidth": 1337,
                                        "tileHeight": 1337,
                                        "tileWidth": 1337
                                    },
                                    "minBufferTime": 1337,
                                    "minFinalSegmentLength": 1337,
                                    "mpdManifestBandwidthType": "MAX",
                                    "mpdProfile": "MAIN_PROFILE",
                                    "ptsOffsetHandlingForBFrames": "MATCH_INITIAL_PTS",
                                    "segmentControl": "SINGLE_FILE",
                                    "segmentLength": 1337,
                                    "segmentLengthControl": "EXACT",
                                    "videoCompositionOffsets": "SIGNED",
                                    "writeSegmentTimelineInRepresentation": "DISABLED"
                                },
                                "fileGroupSettings": {
                                    "destination": "change me",
                                    "destinationSettings": {
                                        "s3Settings": {
                                            "accessControl": {
                                                "cannedAcl": "PUBLIC_READ"
                                            },
                                            "encryption": {
                                                "encryptionType": "SERVER_SIDE_ENCRYPTION_S3",
                                                "kmsEncryptionContext": "change me",
                                                "kmsKeyArn": "change me"
                                            }
                                        }
                                    }
                                },
                                "hlsGroupSettings": {
                                    "adMarkers": [
                                        "ELEMENTAL"
                                    ],
                                    "additionalManifests": [
                                        {
                                            "manifestNameModifier": "change me",
                                            "selectedOutputs": [
                                                "change me"
                                            ]
                                        }
                                    ],
                                    "audioOnlyHeader": "EXCLUDE",
                                    "baseUrl": "change me",
                                    "captionLanguageMappings": [
                                        {
                                            "captionChannel": 1337,
                                            "customLanguageCode": "change me",
                                            "languageCode": "ENG",
                                            "languageDescription": "change me"
                                        }
                                    ],
                                    "captionLanguageSetting": "NONE",
                                    "captionSegmentLengthControl": "LARGE_SEGMENTS",
                                    "clientCache": "DISABLED",
                                    "codecSpecification": "RFC_6381",
                                    "destination": "change me",
                                    "destinationSettings": {
                                        "s3Settings": {
                                            "accessControl": {
                                                "cannedAcl": "PUBLIC_READ"
                                            },
                                            "encryption": {
                                                "encryptionType": "SERVER_SIDE_ENCRYPTION_S3",
                                                "kmsEncryptionContext": "change me",
                                                "kmsKeyArn": "change me"
                                            }
                                        }
                                    },
                                    "directoryStructure": "SINGLE_DIRECTORY",
                                    "encryption": {
                                        "constantInitializationVector": "change me",
                                        "encryptionMethod": "AES128",
                                        "initializationVectorInManifest": "EXCLUDE",
                                        "offlineEncrypted": "DISABLED",
                                        "spekeKeyProvider": {
                                            "certificateArn": "change me",
                                            "resourceId": "change me",
                                            "systemIds": [
                                                "change me"
                                            ],
                                            "url": "change me"
                                        },
                                        "staticKeyProvider": {
                                            "keyFormat": "change me",
                                            "keyFormatVersions": "change me",
                                            "staticKeyValue": "change me",
                                            "url": "change me"
                                        },
                                        "type": "STATIC_KEY"
                                    },
                                    "imageBasedTrickPlay": "NONE",
                                    "imageBasedTrickPlaySettings": {
                                        "intervalCadence": "FOLLOW_IFRAME",
                                        "thumbnailHeight": 1337,
                                        "thumbnailInterval": 1337,
                                        "thumbnailWidth": 1337,
                                        "tileHeight": 1337,
                                        "tileWidth": 1337
                                    },
                                    "manifestCompression": "NONE",
                                    "manifestDurationFormat": "INTEGER",
                                    "minFinalSegmentLength": 1337,
                                    "minSegmentLength": 1337,
                                    "outputSelection": "SEGMENTS_ONLY",
                                    "programDateTime": "EXCLUDE",
                                    "programDateTimePeriod": 1337,
                                    "segmentControl": "SINGLE_FILE",
                                    "segmentLength": 1337,
                                    "segmentLengthControl": "EXACT",
                                    "segmentsPerSubdirectory": 1337,
                                    "streamInfResolution": "EXCLUDE",
                                    "targetDurationCompatibilityMode": "SPEC_COMPLIANT",
                                    "timedMetadataId3Frame": "NONE",
                                    "timedMetadataId3Period": 1337,
                                    "timestampDeltaMilliseconds": 1337
                                },
                                "msSmoothGroupSettings": {
                                    "additionalManifests": [
                                        {
                                            "manifestNameModifier": "change me",
                                            "selectedOutputs": [
                                                "change me"
                                            ]
                                        }
                                    ],
                                    "audioDeduplication": "NONE",
                                    "destination": "change me",
                                    "destinationSettings": {
                                        "s3Settings": {
                                            "accessControl": {
                                                "cannedAcl": "PUBLIC_READ"
                                            },
                                            "encryption": {
                                                "encryptionType": "SERVER_SIDE_ENCRYPTION_S3",
                                                "kmsEncryptionContext": "change me",
                                                "kmsKeyArn": "change me"
                                            }
                                        }
                                    },
                                    "encryption": {
                                        "spekeKeyProvider": {
                                            "certificateArn": "change me",
                                            "resourceId": "change me",
                                            "systemIds": [
                                                "change me"
                                            ],
                                            "url": "change me"
                                        }
                                    },
                                    "fragmentLength": 1337,
                                    "fragmentLengthControl": "EXACT",
                                    "manifestEncoding": "UTF8"
                                },
                                "type": "CMAF_GROUP_SETTINGS"
                            },
                            "outputs": [
                                {
                                    "audioDescriptions": [
                                        {
                                            "audioChannelTaggingSettings": {
                                                "channelTag": "C"
                                            },
                                            "audioNormalizationSettings": {
                                                "algorithm": "ITU_BS_1770_4",
                                                "algorithmControl": "CORRECT_AUDIO",
                                                "correctionGateLevel": 1337,
                                                "loudnessLogging": "LOG",
                                                "peakCalculation": "NONE",
                                                "targetLkfs": 1337,
                                                "truePeakLimiterThreshold": 1337
                                            },
                                            "audioSourceName": "change me",
                                            "audioType": 1337,
                                            "audioTypeControl": "FOLLOW_INPUT",
                                            "codecSettings": {
                                                "aacSettings": {
                                                    "audioDescriptionBroadcasterMix": "NORMAL",
                                                    "bitrate": 1337,
                                                    "codecProfile": "HEV1",
                                                    "codingMode": "CODING_MODE_1_0",
                                                    "rateControlMode": "CBR",
                                                    "rawFormat": "NONE",
                                                    "sampleRate": 1337,
                                                    "specification": "MPEG4",
                                                    "vbrQuality": "HIGH"
                                                },
                                                "ac3Settings": {
                                                    "bitrate": 1337,
                                                    "bitstreamMode": "COMMENTARY",
                                                    "codingMode": "CODING_MODE_1_0",
                                                    "dialnorm": 1337,
                                                    "dynamicRangeCompressionLine": "NONE",
                                                    "dynamicRangeCompressionProfile": "NONE",
                                                    "dynamicRangeCompressionRf": "NONE",
                                                    "lfeFilter": "DISABLED",
                                                    "metadataControl": "FOLLOW_INPUT",
                                                    "sampleRate": 1337
                                                },
                                                "aiffSettings": {
                                                    "bitDepth": 1337,
                                                    "channels": 1337,
                                                    "sampleRate": 1337
                                                },
                                                "codec": "AAC",
                                                "eac3AtmosSettings": {
                                                    "bitrate": 1337,
                                                    "bitstreamMode": "COMPLETE_MAIN",
                                                    "codingMode": "CODING_MODE_5_1_4",
                                                    "dialogueIntelligence": "DISABLED",
                                                    "downmixControl": "SPECIFIED",
                                                    "dynamicRangeCompressionLine": "NONE",
                                                    "dynamicRangeCompressionRf": "NONE",
                                                    "dynamicRangeControl": "SPECIFIED",
                                                    "loRoCenterMixLevel": 1337,
                                                    "loRoSurroundMixLevel": 1337,
                                                    "ltRtCenterMixLevel": 1337,
                                                    "ltRtSurroundMixLevel": 1337,
                                                    "meteringMode": "ITU_BS_1770_4",
                                                    "sampleRate": 1337,
                                                    "speechThreshold": 1337,
                                                    "stereoDownmix": "STEREO",
                                                    "surroundExMode": "DISABLED"
                                                },
                                                "eac3Settings": {
                                                    "attenuationControl": "NONE",
                                                    "bitrate": 1337,
                                                    "bitstreamMode": "COMPLETE_MAIN",
                                                    "codingMode": "CODING_MODE_1_0",
                                                    "dcFilter": "DISABLED",
                                                    "dialnorm": 1337,
                                                    "dynamicRangeCompressionLine": "NONE",
                                                    "dynamicRangeCompressionRf": "NONE",
                                                    "lfeControl": "LFE",
                                                    "lfeFilter": "DISABLED",
                                                    "loRoCenterMixLevel": 1337,
                                                    "loRoSurroundMixLevel": 1337,
                                                    "ltRtCenterMixLevel": 1337,
                                                    "ltRtSurroundMixLevel": 1337,
                                                    "metadataControl": "FOLLOW_INPUT",
                                                    "passthroughControl": "NO_PASSTHROUGH",
                                                    "phaseControl": "NO_SHIFT",
                                                    "sampleRate": 1337,
                                                    "stereoDownmix": "DPL2",
                                                    "surroundExMode": "DISABLED",
                                                    "surroundMode": "DISABLED"
                                                },
                                                "mp2Settings": {
                                                    "bitrate": 1337,
                                                    "channels": 1337,
                                                    "sampleRate": 1337
                                                },
                                                "mp3Settings": {
                                                    "bitrate": 1337,
                                                    "channels": 1337,
                                                    "rateControlMode": "CBR",
                                                    "sampleRate": 1337,
                                                    "vbrQuality": 1337
                                                },
                                                "opusSettings": {
                                                    "bitrate": 1337,
                                                    "channels": 1337,
                                                    "sampleRate": 1337
                                                },
                                                "vorbisSettings": {
                                                    "channels": 1337,
                                                    "sampleRate": 1337,
                                                    "vbrQuality": 1337
                                                },
                                                "wavSettings": {
                                                    "bitDepth": 1337,
                                                    "channels": 1337,
                                                    "format": "RIFF",
                                                    "sampleRate": 1337
                                                }
                                            },
                                            "customLanguageCode": "change me",
                                            "languageCode": "ENG",
                                            "languageCodeControl": "FOLLOW_INPUT",
                                            "remixSettings": {
                                                "channelMapping": {
                                                    "outputChannels": [
                                                        {
                                                            "inputChannels": [
                                                                1337
                                                            ],
                                                            "inputChannelsFineTune": [
                                                                1337
                                                            ]
                                                        }
                                                    ]
                                                },
                                                "channelsIn": 1337,
                                                "channelsOut": 1337
                                            },
                                            "streamName": "change me"
                                        }
                                    ],
                                    "captionDescriptions": [
                                        {
                                            "captionSelectorName": "change me",
                                            "customLanguageCode": "change me",
                                            "destinationSettings": {
                                                "burninDestinationSettings": {
                                                    "alignment": "AUTO",
                                                    "applyFontColor": "ALL_TEXT",
                                                    "backgroundColor": "AUTO",
                                                    "backgroundOpacity": 1337,
                                                    "fallbackFont": "BEST_MATCH",
                                                    "fontColor": "AUTO",
                                                    "fontOpacity": 1337,
                                                    "fontResolution": 1337,
                                                    "fontScript": "AUTOMATIC",
                                                    "fontSize": 1337,
                                                    "hexFontColor": "change me",
                                                    "outlineColor": "AUTO",
                                                    "outlineSize": 1337,
                                                    "shadowColor": "AUTO",
                                                    "shadowOpacity": 1337,
                                                    "shadowXOffset": 1337,
                                                    "shadowYOffset": 1337,
                                                    "stylePassthrough": "DISABLED",
                                                    "teletextSpacing": "AUTO",
                                                    "xPosition": 1337,
                                                    "yPosition": 1337
                                                },
                                                "destinationType": "EMBEDDED",
                                                "dvbSubDestinationSettings": {
                                                    "alignment": "AUTO",
                                                    "applyFontColor": "ALL_TEXT",
                                                    "backgroundColor": "AUTO",
                                                    "backgroundOpacity": 1337,
                                                    "ddsHandling": "NONE",
                                                    "ddsXCoordinate": 1337,
                                                    "ddsYCoordinate": 1337,
                                                    "fallbackFont": "BEST_MATCH",
                                                    "fontColor": "AUTO",
                                                    "fontOpacity": 1337,
                                                    "fontResolution": 1337,
                                                    "fontScript": "AUTOMATIC",
                                                    "fontSize": 1337,
                                                    "height": 1337,
                                                    "hexFontColor": "change me",
                                                    "outlineColor": "AUTO",
                                                    "outlineSize": 1337,
                                                    "shadowColor": "AUTO",
                                                    "shadowOpacity": 1337,
                                                    "shadowXOffset": 1337,
                                                    "shadowYOffset": 1337,
                                                    "stylePassthrough": "DISABLED",
                                                    "subtitlingType": "STANDARD",
                                                    "teletextSpacing": "AUTO",
                                                    "width": 1337,
                                                    "xPosition": 1337,
                                                    "yPosition": 1337
                                                },
                                                "embeddedDestinationSettings": {
                                                    "destination608ChannelNumber": 1337,
                                                    "destination708ServiceNumber": 1337
                                                },
                                                "imscDestinationSettings": {
                                                    "accessibility": "DISABLED",
                                                    "stylePassthrough": "DISABLED"
                                                },
                                                "sccDestinationSettings": {
                                                    "framerate": "FRAMERATE_23_97"
                                                },
                                                "srtDestinationSettings": {
                                                    "stylePassthrough": "DISABLED"
                                                },
                                                "teletextDestinationSettings": {
                                                    "pageNumber": "change me",
                                                    "pageTypes": [
                                                        "PAGE_TYPE_INITIAL"
                                                    ]
                                                },
                                                "ttmlDestinationSettings": {
                                                    "stylePassthrough": "DISABLED"
                                                },
                                                "webvttDestinationSettings": {
                                                    "accessibility": "DISABLED",
                                                    "stylePassthrough": "DISABLED"
                                                }
                                            },
                                            "languageCode": "ENG",
                                            "languageDescription": "change me"
                                        }
                                    ],
                                    "containerSettings": {
                                        "cmfcSettings": {
                                            "audioDuration": "DEFAULT_CODEC_DURATION",
                                            "audioGroupId": "change me",
                                            "audioRenditionSets": "change me",
                                            "audioTrackType": "ALTERNATE_AUDIO_AUTO_SELECT",
                                            "descriptiveVideoServiceFlag": "FLAG",
                                            "iFrameOnlyManifest": "EXCLUDE",
                                            "klvMetadata": "NONE",
                                            "manifestMetadataSignaling": "DISABLED",
                                            "scte35Esam": "NONE",
                                            "scte35Source": "NONE",
                                            "timedMetadata": "NONE",
                                            "timedMetadataBoxVersion": "VERSION_0",
                                            "timedMetadataSchemeIdUri": "change me",
                                            "timedMetadataValue": "change me"
                                        },
                                        "container": "CMFC",
                                        "f4vSettings": {
                                            "moovPlacement": "NORMAL"
                                        },
                                        "m2tsSettings": {
                                            "audioBufferModel": "ATSC",
                                            "audioDuration": "DEFAULT_CODEC_DURATION",
                                            "audioFramesPerPes": 1337,
                                            "audioPids": [
                                                1337
                                            ],
                                            "bitrate": 1337,
                                            "bufferModel": "NONE",
                                            "dataPTSControl": "AUTO",
                                            "dvbNitSettings": {
                                                "networkId": 1337,
                                                "networkName": "change me",
                                                "nitInterval": 1337
                                            },
                                            "dvbSdtSettings": {
                                                "outputSdt": "SDT_NONE",
                                                "sdtInterval": 1337,
                                                "serviceName": "change me",
                                                "serviceProviderName": "change me"
                                            },
                                            "dvbSubPids": [
                                                1337
                                            ],
                                            "dvbTdtSettings": {
                                                "tdtInterval": 1337
                                            },
                                            "dvbTeletextPid": 1337,
                                            "ebpAudioInterval": "VIDEO_INTERVAL",
                                            "ebpPlacement": "VIDEO_PID",
                                            "esRateInPes": "EXCLUDE",
                                            "forceTsVideoEbpOrder": "FORCE",
                                            "fragmentTime": 1337,
                                            "klvMetadata": "NONE",
                                            "maxPcrInterval": 1337,
                                            "minEbpInterval": 1337,
                                            "nielsenId3": "NONE",
                                            "nullPacketBitrate": 1337,
                                            "patInterval": 1337,
                                            "pcrControl": "CONFIGURED_PCR_PERIOD",
                                            "pcrPid": 1337,
                                            "pmtInterval": 1337,
                                            "pmtPid": 1337,
                                            "privateMetadataPid": 1337,
                                            "programNumber": 1337,
                                            "rateMode": "CBR",
                                            "scte35Esam": {
                                                "scte35EsamPid": 1337
                                            },
                                            "scte35Pid": 1337,
                                            "scte35Source": "NONE",
                                            "segmentationMarkers": "NONE",
                                            "segmentationStyle": "MAINTAIN_CADENCE",
                                            "segmentationTime": 1337,
                                            "timedMetadataPid": 1337,
                                            "transportStreamId": 1337,
                                            "videoPid": 1337
                                        },
                                        "m3u8Settings": {
                                            "audioDuration": "DEFAULT_CODEC_DURATION",
                                            "audioFramesPerPes": 1337,
                                            "audioPids": [
                                                1337
                                            ],
                                            "dataPTSControl": "AUTO",
                                            "maxPcrInterval": 1337,
                                            "nielsenId3": "NONE",
                                            "patInterval": 1337,
                                            "pcrControl": "CONFIGURED_PCR_PERIOD",
                                            "pcrPid": 1337,
                                            "pmtInterval": 1337,
                                            "pmtPid": 1337,
                                            "privateMetadataPid": 1337,
                                            "programNumber": 1337,
                                            "scte35Pid": 1337,
                                            "scte35Source": "NONE",
                                            "timedMetadata": "NONE",
                                            "timedMetadataPid": 1337,
                                            "transportStreamId": 1337,
                                            "videoPid": 1337
                                        },
                                        "movSettings": {
                                            "clapAtom": "EXCLUDE",
                                            "cslgAtom": "EXCLUDE",
                                            "mpeg2FourCCControl": "MPEG",
                                            "paddingControl": "NONE",
                                            "reference": "EXTERNAL"
                                        },
                                        "mp4Settings": {
                                            "audioDuration": "DEFAULT_CODEC_DURATION",
                                            "cslgAtom": "EXCLUDE",
                                            "cttsVersion": 1337,
                                            "freeSpaceBox": "EXCLUDE",
                                            "moovPlacement": "NORMAL",
                                            "mp4MajorBrand": "change me"
                                        },
                                        "mpdSettings": {
                                            "accessibilityCaptionHints": "EXCLUDE",
                                            "audioDuration": "DEFAULT_CODEC_DURATION",
                                            "captionContainerType": "FRAGMENTED_MP4",
                                            "klvMetadata": "NONE",
                                            "manifestMetadataSignaling": "DISABLED",
                                            "scte35Esam": "NONE",
                                            "scte35Source": "NONE",
                                            "timedMetadata": "NONE",
                                            "timedMetadataBoxVersion": "VERSION_0",
                                            "timedMetadataSchemeIdUri": "change me",
                                            "timedMetadataValue": "change me"
                                        },
                                        "mxfSettings": {
                                            "afdSignaling": "NO_COPY",
                                            "profile": "D_10",
                                            "xavcProfileSettings": {
                                                "durationMode": "ALLOW_ANY_DURATION",
                                                "maxAncDataSize": 1337
                                            }
                                        }
                                    },
                                    "extension": "change me",
                                    "nameModifier": "change me",
                                    "outputSettings": {
                                        "hlsSettings": {
                                            "audioGroupId": "change me",
                                            "audioOnlyContainer": "AUTOMATIC",
                                            "audioRenditionSets": "change me",
                                            "audioTrackType": "ALTERNATE_AUDIO_AUTO_SELECT",
                                            "descriptiveVideoServiceFlag": "FLAG",
                                            "iFrameOnlyManifest": "EXCLUDE",
                                            "segmentModifier": "change me"
                                        }
                                    },
                                    "preset": "change me",
                                    "videoDescription": {
                                        "afdSignaling": "NONE",
                                        "antiAlias": "DISABLED",
                                        "codecSettings": {
                                            "av1Settings": {
                                                "adaptiveQuantization": "HIGH",
                                                "bitDepth": "BIT_8",
                                                "framerateControl": "SPECIFIED",
                                                "framerateConversionAlgorithm": "INTERPOLATE",
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "gopSize": 1337,
                                                "maxBitrate": 1337,
                                                "numberBFramesBetweenReferenceFrames": 1337,
                                                "qvbrSettings": {
                                                    "qvbrQualityLevel": 1337,
                                                    "qvbrQualityLevelFineTune": 1337
                                                },
                                                "rateControlMode": "QVBR",
                                                "slices": 1337,
                                                "spatialAdaptiveQuantization": "DISABLED"
                                            },
                                            "avcIntraSettings": {
                                                "avcIntraClass": "CLASS_4K_2K",
                                                "avcIntraUhdSettings": {
                                                    "qualityTuningLevel": "SINGLE_PASS"
                                                },
                                                "framerateControl": "SPECIFIED",
                                                "framerateConversionAlgorithm": "INTERPOLATE",
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "interlaceMode": "PROGRESSIVE",
                                                "scanTypeConversionMode": "INTERLACED_OPTIMIZE",
                                                "slowPal": "DISABLED",
                                                "telecine": "NONE"
                                            },
                                            "codec": "AV1",
                                            "frameCaptureSettings": {
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "maxCaptures": 1337,
                                                "quality": 1337
                                            },
                                            "h264Settings": {
                                                "adaptiveQuantization": "HIGH",
                                                "bandwidthReductionFilter": {
                                                    "sharpening": "HIGH",
                                                    "strength": "HIGH"
                                                },
                                                "bitrate": 1337,
                                                "codecLevel": "AUTO",
                                                "codecProfile": "HIGH",
                                                "dynamicSubGop": "ADAPTIVE",
                                                "entropyEncoding": "CABAC",
                                                "fieldEncoding": "FORCE_FIELD",
                                                "flickerAdaptiveQuantization": "DISABLED",
                                                "framerateControl": "SPECIFIED",
                                                "framerateConversionAlgorithm": "INTERPOLATE",
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "gopBReference": "DISABLED",
                                                "gopClosedCadence": 1337,
                                                "gopSize": 1337,
                                                "gopSizeUnits": "AUTO",
                                                "hrdBufferFinalFillPercentage": 1337,
                                                "hrdBufferInitialFillPercentage": 1337,
                                                "hrdBufferSize": 1337,
                                                "interlaceMode": "PROGRESSIVE",
                                                "maxBitrate": 1337,
                                                "minIInterval": 1337,
                                                "numberBFramesBetweenReferenceFrames": 1337,
                                                "numberReferenceFrames": 1337,
                                                "parControl": "SPECIFIED",
                                                "parDenominator": 1337,
                                                "parNumerator": 1337,
                                                "qualityTuningLevel": "SINGLE_PASS",
                                                "qvbrSettings": {
                                                    "maxAverageBitrate": 1337,
                                                    "qvbrQualityLevel": 1337,
                                                    "qvbrQualityLevelFineTune": 1337
                                                },
                                                "rateControlMode": "QVBR",
                                                "repeatPps": "DISABLED",
                                                "scanTypeConversionMode": "INTERLACED_OPTIMIZE",
                                                "sceneChangeDetect": "DISABLED",
                                                "slices": 1337,
                                                "slowPal": "DISABLED",
                                                "softness": 1337,
                                                "spatialAdaptiveQuantization": "DISABLED",
                                                "syntax": "DEFAULT",
                                                "telecine": "NONE",
                                                "temporalAdaptiveQuantization": "DISABLED",
                                                "unregisteredSeiTimecode": "DISABLED"
                                            },
                                            "h265Settings": {
                                                "adaptiveQuantization": "AUTO",
                                                "alternateTransferFunctionSei": "DISABLED",
                                                "bitrate": 1337,
                                                "codecLevel": "AUTO",
                                                "codecProfile": "MAIN10_HIGH",
                                                "dynamicSubGop": "ADAPTIVE",
                                                "flickerAdaptiveQuantization": "DISABLED",
                                                "framerateControl": "SPECIFIED",
                                                "framerateConversionAlgorithm": "INTERPOLATE",
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "gopBReference": "DISABLED",
                                                "gopClosedCadence": 1337,
                                                "gopSize": 1337,
                                                "gopSizeUnits": "AUTO",
                                                "hrdBufferFinalFillPercentage": 1337,
                                                "hrdBufferInitialFillPercentage": 1337,
                                                "hrdBufferSize": 1337,
                                                "interlaceMode": "PROGRESSIVE",
                                                "maxBitrate": 1337,
                                                "minIInterval": 1337,
                                                "numberBFramesBetweenReferenceFrames": 1337,
                                                "numberReferenceFrames": 1337,
                                                "parControl": "SPECIFIED",
                                                "parDenominator": 1337,
                                                "parNumerator": 1337,
                                                "qualityTuningLevel": "SINGLE_PASS",
                                                "qvbrSettings": {
                                                    "maxAverageBitrate": 1337,
                                                    "qvbrQualityLevel": 1337,
                                                    "qvbrQualityLevelFineTune": 1337
                                                },
                                                "rateControlMode": "QVBR",
                                                "sampleAdaptiveOffsetFilterMode": "ADAPTIVE",
                                                "scanTypeConversionMode": "INTERLACED_OPTIMIZE",
                                                "sceneChangeDetect": "DISABLED",
                                                "slices": 1337,
                                                "slowPal": "DISABLED",
                                                "spatialAdaptiveQuantization": "DISABLED",
                                                "telecine": "NONE",
                                                "temporalAdaptiveQuantization": "DISABLED",
                                                "temporalIds": "DISABLED",
                                                "tiles": "DISABLED",
                                                "unregisteredSeiTimecode": "DISABLED",
                                                "writeMp4PackagingType": "HEV1"
                                            },
                                            "mpeg2Settings": {
                                                "adaptiveQuantization": "HIGH",
                                                "bitrate": 1337,
                                                "codecLevel": "HIGH",
                                                "codecProfile": "PROFILE_422",
                                                "dynamicSubGop": "ADAPTIVE",
                                                "framerateControl": "SPECIFIED",
                                                "framerateConversionAlgorithm": "INTERPOLATE",
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "gopClosedCadence": 1337,
                                                "gopSize": 1337,
                                                "gopSizeUnits": "SECONDS",
                                                "hrdBufferFinalFillPercentage": 1337,
                                                "hrdBufferInitialFillPercentage": 1337,
                                                "hrdBufferSize": 1337,
                                                "interlaceMode": "PROGRESSIVE",
                                                "intraDcPrecision": "INTRA_DC_PRECISION_8",
                                                "maxBitrate": 1337,
                                                "minIInterval": 1337,
                                                "numberBFramesBetweenReferenceFrames": 1337,
                                                "parControl": "SPECIFIED",
                                                "parDenominator": 1337,
                                                "parNumerator": 1337,
                                                "qualityTuningLevel": "SINGLE_PASS",
                                                "rateControlMode": "CBR",
                                                "scanTypeConversionMode": "INTERLACED_OPTIMIZE",
                                                "sceneChangeDetect": "DISABLED",
                                                "slowPal": "DISABLED",
                                                "softness": 1337,
                                                "spatialAdaptiveQuantization": "DISABLED",
                                                "syntax": "DEFAULT",
                                                "telecine": "NONE",
                                                "temporalAdaptiveQuantization": "DISABLED"
                                            },
                                            "proresSettings": {
                                                "chromaSampling": "PRESERVE_444_SAMPLING",
                                                "codecProfile": "APPLE_PRORES_422",
                                                "framerateControl": "SPECIFIED",
                                                "framerateConversionAlgorithm": "INTERPOLATE",
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "interlaceMode": "PROGRESSIVE",
                                                "parControl": "SPECIFIED",
                                                "parDenominator": 1337,
                                                "parNumerator": 1337,
                                                "scanTypeConversionMode": "INTERLACED_OPTIMIZE",
                                                "slowPal": "DISABLED",
                                                "telecine": "NONE"
                                            },
                                            "vc3Settings": {
                                                "framerateControl": "SPECIFIED",
                                                "framerateConversionAlgorithm": "INTERPOLATE",
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "interlaceMode": "INTERLACED",
                                                "scanTypeConversionMode": "INTERLACED",
                                                "slowPal": "DISABLED",
                                                "telecine": "NONE",
                                                "vc3Class": "CLASS_145_8BIT"
                                            },
                                            "vp8Settings": {
                                                "bitrate": 1337,
                                                "framerateControl": "SPECIFIED",
                                                "framerateConversionAlgorithm": "INTERPOLATE",
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "gopSize": 1337,
                                                "hrdBufferSize": 1337,
                                                "maxBitrate": 1337,
                                                "parControl": "SPECIFIED",
                                                "parDenominator": 1337,
                                                "parNumerator": 1337,
                                                "qualityTuningLevel": "MULTI_PASS",
                                                "rateControlMode": "VBR"
                                            },
                                            "vp9Settings": {
                                                "bitrate": 1337,
                                                "framerateControl": "SPECIFIED",
                                                "framerateConversionAlgorithm": "INTERPOLATE",
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "gopSize": 1337,
                                                "hrdBufferSize": 1337,
                                                "maxBitrate": 1337,
                                                "parControl": "SPECIFIED",
                                                "parDenominator": 1337,
                                                "parNumerator": 1337,
                                                "qualityTuningLevel": "MULTI_PASS",
                                                "rateControlMode": "VBR"
                                            },
                                            "xavcSettings": {
                                                "adaptiveQuantization": "HIGH",
                                                "entropyEncoding": "AUTO",
                                                "framerateControl": "SPECIFIED",
                                                "framerateConversionAlgorithm": "INTERPOLATE",
                                                "framerateDenominator": 1337,
                                                "framerateNumerator": 1337,
                                                "profile": "XAVC_4K",
                                                "slowPal": "DISABLED",
                                                "softness": 1337,
                                                "spatialAdaptiveQuantization": "DISABLED",
                                                "temporalAdaptiveQuantization": "DISABLED",
                                                "xavc4kIntraCbgProfileSettings": {
                                                    "xavcClass": "CLASS_100"
                                                },
                                                "xavc4kIntraVbrProfileSettings": {
                                                    "xavcClass": "CLASS_100"
                                                },
                                                "xavc4kProfileSettings": {
                                                    "bitrateClass": "BITRATE_CLASS_100",
                                                    "codecProfile": "HIGH",
                                                    "flickerAdaptiveQuantization": "DISABLED",
                                                    "gopBReference": "DISABLED",
                                                    "gopClosedCadence": 1337,
                                                    "hrdBufferSize": 1337,
                                                    "qualityTuningLevel": "SINGLE_PASS",
                                                    "slices": 1337
                                                },
                                                "xavcHdIntraCbgProfileSettings": {
                                                    "xavcClass": "CLASS_100"
                                                },
                                                "xavcHdProfileSettings": {
                                                    "bitrateClass": "BITRATE_CLASS_25",
                                                    "flickerAdaptiveQuantization": "DISABLED",
                                                    "gopBReference": "DISABLED",
                                                    "gopClosedCadence": 1337,
                                                    "hrdBufferSize": 1337,
                                                    "interlaceMode": "PROGRESSIVE",
                                                    "qualityTuningLevel": "SINGLE_PASS",
                                                    "slices": 1337,
                                                    "telecine": "NONE"
                                                }
                                            }
                                        },
                                        "colorMetadata": "IGNORE",
                                        "crop": {
                                            "height": 1337,
                                            "width": 1337,
                                            "x": 1337,
                                            "y": 1337
                                        },
                                        "dropFrameTimecode": "DISABLED",
                                        "fixedAfd": 1337,
                                        "height": 1337,
                                        "position": {
                                            "height": 1337,
                                            "width": 1337,
                                            "x": 1337,
                                            "y": 1337
                                        },
                                        "respondToAfd": "NONE",
                                        "scalingBehavior": "DEFAULT",
                                        "sharpness": 1337,
                                        "timecodeInsertion": "DISABLED",
                                        "videoPreprocessors": {
                                            "colorCorrector": {
                                                "brightness": 1337,
                                                "clipLimits": {
                                                    "maximumRGBTolerance": 1337,
                                                    "maximumYUV": 1337,
                                                    "minimumRGBTolerance": 1337,
                                                    "minimumYUV": 1337
                                                },
                                                "colorSpaceConversion": "NONE",
                                                "contrast": 1337,
                                                "hdr10Metadata": {
                                                    "bluePrimaryX": 1337,
                                                    "bluePrimaryY": 1337,
                                                    "greenPrimaryX": 1337,
                                                    "greenPrimaryY": 1337,
                                                    "maxContentLightLevel": 1337,
                                                    "maxFrameAverageLightLevel": 1337,
                                                    "maxLuminance": 1337,
                                                    "minLuminance": 1337,
                                                    "redPrimaryX": 1337,
                                                    "redPrimaryY": 1337,
                                                    "whitePointX": 1337,
                                                    "whitePointY": 1337
                                                },
                                                "hdrToSdrToneMapper": "PRESERVE_DETAILS",
                                                "hue": 1337,
                                                "sampleRangeConversion": "NONE",
                                                "saturation": 1337,
                                                "sdrReferenceWhiteLevel": 1337
                                            },
                                            "deinterlacer": {
                                                "algorithm": "INTERPOLATE",
                                                "control": "NORMAL",
                                                "mode": "DEINTERLACE"
                                            },
                                            "dolbyVision": {
                                                "l6Metadata": {
                                                    "maxCll": 1337,
                                                    "maxFall": 1337
                                                },
                                                "l6Mode": "PASSTHROUGH",
                                                "mapping": "HDR10_NOMAP",
                                                "profile": "PROFILE_5"
                                            },
                                            "hdr10Plus": {
                                                "masteringMonitorNits": 1337,
                                                "targetMonitorNits": 1337
                                            },
                                            "imageInserter": {
                                                "insertableImages": [
                                                    {
                                                        "duration": 1337,
                                                        "fadeIn": 1337,
                                                        "fadeOut": 1337,
                                                        "height": 1337,
                                                        "imageInserterInput": "change me",
                                                        "imageX": 1337,
                                                        "imageY": 1337,
                                                        "layer": 1337,
                                                        "opacity": 1337,
                                                        "startTime": "change me",
                                                        "width": 1337
                                                    }
                                                ],
                                                "sdrReferenceWhiteLevel": 1337
                                            },
                                            "noiseReducer": {
                                                "filter": "BILATERAL",
                                                "filterSettings": {
                                                    "strength": 1337
                                                },
                                                "spatialFilterSettings": {
                                                    "postFilterSharpenStrength": 1337,
                                                    "speed": 1337,
                                                    "strength": 1337
                                                },
                                                "temporalFilterSettings": {
                                                    "aggressiveMode": 1337,
                                                    "postTemporalSharpening": "DISABLED",
                                                    "postTemporalSharpeningStrength": "HIGH",
                                                    "speed": 1337,
                                                    "strength": 1337
                                                }
                                            },
                                            "partnerWatermarking": {
                                                "nexguardFileMarkerSettings": {
                                                    "license": "change me",
                                                    "payload": 1337,
                                                    "preset": "change me",
                                                    "strength": "DEFAULT"
                                                }
                                            },
                                            "timecodeBurnin": {
                                                "fontSize": 1337,
                                                "position": "BOTTOM_CENTER",
                                                "prefix": "change me"
                                            }
                                        },
                                        "width": 1337
                                    }
                                }
                            ]
                        }
                    ],
                    "timecodeConfig": {
                        "anchor": "change me",
                        "source": "EMBEDDED",
                        "start": "change me",
                        "timestampOffset": "change me"
                    },
                    "timedMetadataInsertion": {
                        "id3Insertions": [
                            {
                                "id3": "change me",
                                "timecode": "change me"
                            }
                        ]
                    }
                },
                "simulateReservedQueue": "DISABLED",
                "statusUpdateInterval": "SECONDS_10",
                "tags": {
                    "change me": "change me"
                },
                "userMetadata": {
                    "change me": "change me"
                }
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
