<?php

namespace AsyncAws\MediaConvert\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\MediaConvert\Enum\AacAudioDescriptionBroadcasterMix;
use AsyncAws\MediaConvert\Enum\AacCodecProfile;
use AsyncAws\MediaConvert\Enum\AacCodingMode;
use AsyncAws\MediaConvert\Enum\AacLoudnessMeasurementMode;
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
use AsyncAws\MediaConvert\Enum\AccelerationStatus;
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
use AsyncAws\MediaConvert\Enum\Av1FilmGrainSynthesis;
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
use AsyncAws\MediaConvert\Enum\CaptionSourceByteRateLimit;
use AsyncAws\MediaConvert\Enum\CaptionSourceConvertPaintOnToPopOn;
use AsyncAws\MediaConvert\Enum\CaptionSourceType;
use AsyncAws\MediaConvert\Enum\CaptionSourceUpconvertSTLToTeletext;
use AsyncAws\MediaConvert\Enum\ChromaPositionMode;
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
use AsyncAws\MediaConvert\Enum\CmfcC2paManifest;
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
use AsyncAws\MediaConvert\Enum\DynamicAudioSelectorType;
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
use AsyncAws\MediaConvert\Enum\FrameControl;
use AsyncAws\MediaConvert\Enum\FrameMetricType;
use AsyncAws\MediaConvert\Enum\GifFramerateControl;
use AsyncAws\MediaConvert\Enum\GifFramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\H264AdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264CodecLevel;
use AsyncAws\MediaConvert\Enum\H264CodecProfile;
use AsyncAws\MediaConvert\Enum\H264DynamicSubGop;
use AsyncAws\MediaConvert\Enum\H264EndOfStreamMarkers;
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
use AsyncAws\MediaConvert\Enum\H264SaliencyAwareEncoding;
use AsyncAws\MediaConvert\Enum\H264ScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\H264SceneChangeDetect;
use AsyncAws\MediaConvert\Enum\H264SlowPal;
use AsyncAws\MediaConvert\Enum\H264SpatialAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264Syntax;
use AsyncAws\MediaConvert\Enum\H264Telecine;
use AsyncAws\MediaConvert\Enum\H264TemporalAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H264UnregisteredSeiTimecode;
use AsyncAws\MediaConvert\Enum\H264WriteMp4PackagingType;
use AsyncAws\MediaConvert\Enum\H265AdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H265AlternateTransferFunctionSei;
use AsyncAws\MediaConvert\Enum\H265CodecLevel;
use AsyncAws\MediaConvert\Enum\H265CodecProfile;
use AsyncAws\MediaConvert\Enum\H265Deblocking;
use AsyncAws\MediaConvert\Enum\H265DynamicSubGop;
use AsyncAws\MediaConvert\Enum\H265EndOfStreamMarkers;
use AsyncAws\MediaConvert\Enum\H265FlickerAdaptiveQuantization;
use AsyncAws\MediaConvert\Enum\H265FramerateControl;
use AsyncAws\MediaConvert\Enum\H265FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\H265GopBReference;
use AsyncAws\MediaConvert\Enum\H265GopSizeUnits;
use AsyncAws\MediaConvert\Enum\H265InterlaceMode;
use AsyncAws\MediaConvert\Enum\H265MvOverPictureBoundaries;
use AsyncAws\MediaConvert\Enum\H265MvTemporalPredictor;
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
use AsyncAws\MediaConvert\Enum\H265TilePadding;
use AsyncAws\MediaConvert\Enum\H265Tiles;
use AsyncAws\MediaConvert\Enum\H265TreeBlockSize;
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
use AsyncAws\MediaConvert\Enum\HlsProgressiveWriteHlsManifest;
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
use AsyncAws\MediaConvert\Enum\InputTimecodeSource;
use AsyncAws\MediaConvert\Enum\JobPhase;
use AsyncAws\MediaConvert\Enum\JobStatus;
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
use AsyncAws\MediaConvert\Enum\M2tsPreventBufferUnderflow;
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
use AsyncAws\MediaConvert\Enum\Mp2AudioDescriptionMix;
use AsyncAws\MediaConvert\Enum\Mp3RateControlMode;
use AsyncAws\MediaConvert\Enum\Mp4C2paManifest;
use AsyncAws\MediaConvert\Enum\Mp4CslgAtom;
use AsyncAws\MediaConvert\Enum\Mp4FreeSpaceBox;
use AsyncAws\MediaConvert\Enum\Mp4MoovPlacement;
use AsyncAws\MediaConvert\Enum\MpdAccessibilityCaptionHints;
use AsyncAws\MediaConvert\Enum\MpdAudioDuration;
use AsyncAws\MediaConvert\Enum\MpdC2paManifest;
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
use AsyncAws\MediaConvert\Enum\PresetSpeke20Audio;
use AsyncAws\MediaConvert\Enum\PresetSpeke20Video;
use AsyncAws\MediaConvert\Enum\ProresChromaSampling;
use AsyncAws\MediaConvert\Enum\ProresCodecProfile;
use AsyncAws\MediaConvert\Enum\ProresFramerateControl;
use AsyncAws\MediaConvert\Enum\ProresFramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\ProresInterlaceMode;
use AsyncAws\MediaConvert\Enum\ProresParControl;
use AsyncAws\MediaConvert\Enum\ProresScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\ProresSlowPal;
use AsyncAws\MediaConvert\Enum\ProresTelecine;
use AsyncAws\MediaConvert\Enum\RemoveRubyReserveAttributes;
use AsyncAws\MediaConvert\Enum\RequiredFlag;
use AsyncAws\MediaConvert\Enum\RespondToAfd;
use AsyncAws\MediaConvert\Enum\RuleType;
use AsyncAws\MediaConvert\Enum\S3ObjectCannedAcl;
use AsyncAws\MediaConvert\Enum\S3ServerSideEncryptionType;
use AsyncAws\MediaConvert\Enum\S3StorageClass;
use AsyncAws\MediaConvert\Enum\SampleRangeConversion;
use AsyncAws\MediaConvert\Enum\ScalingBehavior;
use AsyncAws\MediaConvert\Enum\SccDestinationFramerate;
use AsyncAws\MediaConvert\Enum\ShareStatus;
use AsyncAws\MediaConvert\Enum\SimulateReservedQueue;
use AsyncAws\MediaConvert\Enum\SlowPalPitchCorrection;
use AsyncAws\MediaConvert\Enum\SrtStylePassthrough;
use AsyncAws\MediaConvert\Enum\StatusUpdateInterval;
use AsyncAws\MediaConvert\Enum\TamsGapHandling;
use AsyncAws\MediaConvert\Enum\TeletextPageType;
use AsyncAws\MediaConvert\Enum\TimecodeBurninPosition;
use AsyncAws\MediaConvert\Enum\TimecodeSource;
use AsyncAws\MediaConvert\Enum\TimecodeTrack;
use AsyncAws\MediaConvert\Enum\TimedMetadata;
use AsyncAws\MediaConvert\Enum\TsPtsOffset;
use AsyncAws\MediaConvert\Enum\TtmlStylePassthrough;
use AsyncAws\MediaConvert\Enum\UncompressedFourcc;
use AsyncAws\MediaConvert\Enum\UncompressedFramerateControl;
use AsyncAws\MediaConvert\Enum\UncompressedFramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\UncompressedInterlaceMode;
use AsyncAws\MediaConvert\Enum\UncompressedScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\UncompressedSlowPal;
use AsyncAws\MediaConvert\Enum\UncompressedTelecine;
use AsyncAws\MediaConvert\Enum\Vc3Class;
use AsyncAws\MediaConvert\Enum\Vc3FramerateControl;
use AsyncAws\MediaConvert\Enum\Vc3FramerateConversionAlgorithm;
use AsyncAws\MediaConvert\Enum\Vc3InterlaceMode;
use AsyncAws\MediaConvert\Enum\Vc3ScanTypeConversionMode;
use AsyncAws\MediaConvert\Enum\Vc3SlowPal;
use AsyncAws\MediaConvert\Enum\Vc3Telecine;
use AsyncAws\MediaConvert\Enum\VchipAction;
use AsyncAws\MediaConvert\Enum\VideoCodec;
use AsyncAws\MediaConvert\Enum\VideoOverlayPlayBackMode;
use AsyncAws\MediaConvert\Enum\VideoOverlayUnit;
use AsyncAws\MediaConvert\Enum\VideoSelectorMode;
use AsyncAws\MediaConvert\Enum\VideoSelectorType;
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
use AsyncAws\MediaConvert\ValueObject\AudioPitchCorrectionSettings;
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
use AsyncAws\MediaConvert\ValueObject\InputTamsSettings;
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
use AsyncAws\MediaConvert\ValueObject\PassthroughSettings;
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
            'AudioDescriptionBroadcasterMix' => isset($json['audioDescriptionBroadcasterMix']) ? (!AacAudioDescriptionBroadcasterMix::exists((string) $json['audioDescriptionBroadcasterMix']) ? AacAudioDescriptionBroadcasterMix::UNKNOWN_TO_SDK : (string) $json['audioDescriptionBroadcasterMix']) : null,
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'CodecProfile' => isset($json['codecProfile']) ? (!AacCodecProfile::exists((string) $json['codecProfile']) ? AacCodecProfile::UNKNOWN_TO_SDK : (string) $json['codecProfile']) : null,
            'CodingMode' => isset($json['codingMode']) ? (!AacCodingMode::exists((string) $json['codingMode']) ? AacCodingMode::UNKNOWN_TO_SDK : (string) $json['codingMode']) : null,
            'LoudnessMeasurementMode' => isset($json['loudnessMeasurementMode']) ? (!AacLoudnessMeasurementMode::exists((string) $json['loudnessMeasurementMode']) ? AacLoudnessMeasurementMode::UNKNOWN_TO_SDK : (string) $json['loudnessMeasurementMode']) : null,
            'RapInterval' => isset($json['rapInterval']) ? (int) $json['rapInterval'] : null,
            'RateControlMode' => isset($json['rateControlMode']) ? (!AacRateControlMode::exists((string) $json['rateControlMode']) ? AacRateControlMode::UNKNOWN_TO_SDK : (string) $json['rateControlMode']) : null,
            'RawFormat' => isset($json['rawFormat']) ? (!AacRawFormat::exists((string) $json['rawFormat']) ? AacRawFormat::UNKNOWN_TO_SDK : (string) $json['rawFormat']) : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
            'Specification' => isset($json['specification']) ? (!AacSpecification::exists((string) $json['specification']) ? AacSpecification::UNKNOWN_TO_SDK : (string) $json['specification']) : null,
            'TargetLoudnessRange' => isset($json['targetLoudnessRange']) ? (int) $json['targetLoudnessRange'] : null,
            'VbrQuality' => isset($json['vbrQuality']) ? (!AacVbrQuality::exists((string) $json['vbrQuality']) ? AacVbrQuality::UNKNOWN_TO_SDK : (string) $json['vbrQuality']) : null,
        ]);
    }

    private function populateResultAc3Settings(array $json): Ac3Settings
    {
        return new Ac3Settings([
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'BitstreamMode' => isset($json['bitstreamMode']) ? (!Ac3BitstreamMode::exists((string) $json['bitstreamMode']) ? Ac3BitstreamMode::UNKNOWN_TO_SDK : (string) $json['bitstreamMode']) : null,
            'CodingMode' => isset($json['codingMode']) ? (!Ac3CodingMode::exists((string) $json['codingMode']) ? Ac3CodingMode::UNKNOWN_TO_SDK : (string) $json['codingMode']) : null,
            'Dialnorm' => isset($json['dialnorm']) ? (int) $json['dialnorm'] : null,
            'DynamicRangeCompressionLine' => isset($json['dynamicRangeCompressionLine']) ? (!Ac3DynamicRangeCompressionLine::exists((string) $json['dynamicRangeCompressionLine']) ? Ac3DynamicRangeCompressionLine::UNKNOWN_TO_SDK : (string) $json['dynamicRangeCompressionLine']) : null,
            'DynamicRangeCompressionProfile' => isset($json['dynamicRangeCompressionProfile']) ? (!Ac3DynamicRangeCompressionProfile::exists((string) $json['dynamicRangeCompressionProfile']) ? Ac3DynamicRangeCompressionProfile::UNKNOWN_TO_SDK : (string) $json['dynamicRangeCompressionProfile']) : null,
            'DynamicRangeCompressionRf' => isset($json['dynamicRangeCompressionRf']) ? (!Ac3DynamicRangeCompressionRf::exists((string) $json['dynamicRangeCompressionRf']) ? Ac3DynamicRangeCompressionRf::UNKNOWN_TO_SDK : (string) $json['dynamicRangeCompressionRf']) : null,
            'LfeFilter' => isset($json['lfeFilter']) ? (!Ac3LfeFilter::exists((string) $json['lfeFilter']) ? Ac3LfeFilter::UNKNOWN_TO_SDK : (string) $json['lfeFilter']) : null,
            'MetadataControl' => isset($json['metadataControl']) ? (!Ac3MetadataControl::exists((string) $json['metadataControl']) ? Ac3MetadataControl::UNKNOWN_TO_SDK : (string) $json['metadataControl']) : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
        ]);
    }

    private function populateResultAccelerationSettings(array $json): AccelerationSettings
    {
        return new AccelerationSettings([
            'Mode' => !AccelerationMode::exists((string) $json['mode']) ? AccelerationMode::UNKNOWN_TO_SDK : (string) $json['mode'],
        ]);
    }

    private function populateResultAdvancedInputFilterSettings(array $json): AdvancedInputFilterSettings
    {
        return new AdvancedInputFilterSettings([
            'AddTexture' => isset($json['addTexture']) ? (!AdvancedInputFilterAddTexture::exists((string) $json['addTexture']) ? AdvancedInputFilterAddTexture::UNKNOWN_TO_SDK : (string) $json['addTexture']) : null,
            'Sharpening' => isset($json['sharpening']) ? (!AdvancedInputFilterSharpen::exists((string) $json['sharpening']) ? AdvancedInputFilterSharpen::UNKNOWN_TO_SDK : (string) $json['sharpening']) : null,
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
            'Required' => isset($json['required']) ? (!RequiredFlag::exists((string) $json['required']) ? RequiredFlag::UNKNOWN_TO_SDK : (string) $json['required']) : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
        ]);
    }

    private function populateResultAncillarySourceSettings(array $json): AncillarySourceSettings
    {
        return new AncillarySourceSettings([
            'Convert608To708' => isset($json['convert608To708']) ? (!AncillaryConvert608To708::exists((string) $json['convert608To708']) ? AncillaryConvert608To708::UNKNOWN_TO_SDK : (string) $json['convert608To708']) : null,
            'SourceAncillaryChannelNumber' => isset($json['sourceAncillaryChannelNumber']) ? (int) $json['sourceAncillaryChannelNumber'] : null,
            'TerminateCaptions' => isset($json['terminateCaptions']) ? (!AncillaryTerminateCaptions::exists((string) $json['terminateCaptions']) ? AncillaryTerminateCaptions::UNKNOWN_TO_SDK : (string) $json['terminateCaptions']) : null,
        ]);
    }

    private function populateResultAudioChannelTaggingSettings(array $json): AudioChannelTaggingSettings
    {
        return new AudioChannelTaggingSettings([
            'ChannelTag' => isset($json['channelTag']) ? (!AudioChannelTag::exists((string) $json['channelTag']) ? AudioChannelTag::UNKNOWN_TO_SDK : (string) $json['channelTag']) : null,
            'ChannelTags' => !isset($json['channelTags']) ? null : $this->populateResult__listOfAudioChannelTag($json['channelTags']),
        ]);
    }

    private function populateResultAudioCodecSettings(array $json): AudioCodecSettings
    {
        return new AudioCodecSettings([
            'AacSettings' => empty($json['aacSettings']) ? null : $this->populateResultAacSettings($json['aacSettings']),
            'Ac3Settings' => empty($json['ac3Settings']) ? null : $this->populateResultAc3Settings($json['ac3Settings']),
            'AiffSettings' => empty($json['aiffSettings']) ? null : $this->populateResultAiffSettings($json['aiffSettings']),
            'Codec' => isset($json['codec']) ? (!AudioCodec::exists((string) $json['codec']) ? AudioCodec::UNKNOWN_TO_SDK : (string) $json['codec']) : null,
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
            'AudioPitchCorrectionSettings' => empty($json['audioPitchCorrectionSettings']) ? null : $this->populateResultAudioPitchCorrectionSettings($json['audioPitchCorrectionSettings']),
            'AudioSourceName' => isset($json['audioSourceName']) ? (string) $json['audioSourceName'] : null,
            'AudioType' => isset($json['audioType']) ? (int) $json['audioType'] : null,
            'AudioTypeControl' => isset($json['audioTypeControl']) ? (!AudioTypeControl::exists((string) $json['audioTypeControl']) ? AudioTypeControl::UNKNOWN_TO_SDK : (string) $json['audioTypeControl']) : null,
            'CodecSettings' => empty($json['codecSettings']) ? null : $this->populateResultAudioCodecSettings($json['codecSettings']),
            'CustomLanguageCode' => isset($json['customLanguageCode']) ? (string) $json['customLanguageCode'] : null,
            'LanguageCode' => isset($json['languageCode']) ? (!LanguageCode::exists((string) $json['languageCode']) ? LanguageCode::UNKNOWN_TO_SDK : (string) $json['languageCode']) : null,
            'LanguageCodeControl' => isset($json['languageCodeControl']) ? (!AudioLanguageCodeControl::exists((string) $json['languageCodeControl']) ? AudioLanguageCodeControl::UNKNOWN_TO_SDK : (string) $json['languageCodeControl']) : null,
            'RemixSettings' => empty($json['remixSettings']) ? null : $this->populateResultRemixSettings($json['remixSettings']),
            'StreamName' => isset($json['streamName']) ? (string) $json['streamName'] : null,
        ]);
    }

    private function populateResultAudioNormalizationSettings(array $json): AudioNormalizationSettings
    {
        return new AudioNormalizationSettings([
            'Algorithm' => isset($json['algorithm']) ? (!AudioNormalizationAlgorithm::exists((string) $json['algorithm']) ? AudioNormalizationAlgorithm::UNKNOWN_TO_SDK : (string) $json['algorithm']) : null,
            'AlgorithmControl' => isset($json['algorithmControl']) ? (!AudioNormalizationAlgorithmControl::exists((string) $json['algorithmControl']) ? AudioNormalizationAlgorithmControl::UNKNOWN_TO_SDK : (string) $json['algorithmControl']) : null,
            'CorrectionGateLevel' => isset($json['correctionGateLevel']) ? (int) $json['correctionGateLevel'] : null,
            'LoudnessLogging' => isset($json['loudnessLogging']) ? (!AudioNormalizationLoudnessLogging::exists((string) $json['loudnessLogging']) ? AudioNormalizationLoudnessLogging::UNKNOWN_TO_SDK : (string) $json['loudnessLogging']) : null,
            'PeakCalculation' => isset($json['peakCalculation']) ? (!AudioNormalizationPeakCalculation::exists((string) $json['peakCalculation']) ? AudioNormalizationPeakCalculation::UNKNOWN_TO_SDK : (string) $json['peakCalculation']) : null,
            'TargetLkfs' => isset($json['targetLkfs']) ? (float) $json['targetLkfs'] : null,
            'TruePeakLimiterThreshold' => isset($json['truePeakLimiterThreshold']) ? (float) $json['truePeakLimiterThreshold'] : null,
        ]);
    }

    private function populateResultAudioPitchCorrectionSettings(array $json): AudioPitchCorrectionSettings
    {
        return new AudioPitchCorrectionSettings([
            'SlowPalPitchCorrection' => isset($json['slowPalPitchCorrection']) ? (!SlowPalPitchCorrection::exists((string) $json['slowPalPitchCorrection']) ? SlowPalPitchCorrection::UNKNOWN_TO_SDK : (string) $json['slowPalPitchCorrection']) : null,
        ]);
    }

    private function populateResultAudioSelector(array $json): AudioSelector
    {
        return new AudioSelector([
            'AudioDurationCorrection' => isset($json['audioDurationCorrection']) ? (!AudioDurationCorrection::exists((string) $json['audioDurationCorrection']) ? AudioDurationCorrection::UNKNOWN_TO_SDK : (string) $json['audioDurationCorrection']) : null,
            'CustomLanguageCode' => isset($json['customLanguageCode']) ? (string) $json['customLanguageCode'] : null,
            'DefaultSelection' => isset($json['defaultSelection']) ? (!AudioDefaultSelection::exists((string) $json['defaultSelection']) ? AudioDefaultSelection::UNKNOWN_TO_SDK : (string) $json['defaultSelection']) : null,
            'ExternalAudioFileInput' => isset($json['externalAudioFileInput']) ? (string) $json['externalAudioFileInput'] : null,
            'HlsRenditionGroupSettings' => empty($json['hlsRenditionGroupSettings']) ? null : $this->populateResultHlsRenditionGroupSettings($json['hlsRenditionGroupSettings']),
            'LanguageCode' => isset($json['languageCode']) ? (!LanguageCode::exists((string) $json['languageCode']) ? LanguageCode::UNKNOWN_TO_SDK : (string) $json['languageCode']) : null,
            'Offset' => isset($json['offset']) ? (int) $json['offset'] : null,
            'Pids' => !isset($json['pids']) ? null : $this->populateResult__listOf__integerMin1Max2147483647($json['pids']),
            'ProgramSelection' => isset($json['programSelection']) ? (int) $json['programSelection'] : null,
            'RemixSettings' => empty($json['remixSettings']) ? null : $this->populateResultRemixSettings($json['remixSettings']),
            'SelectorType' => isset($json['selectorType']) ? (!AudioSelectorType::exists((string) $json['selectorType']) ? AudioSelectorType::UNKNOWN_TO_SDK : (string) $json['selectorType']) : null,
            'Streams' => !isset($json['streams']) ? null : $this->populateResult__listOf__integerMin1Max2147483647($json['streams']),
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
            'Type' => isset($json['type']) ? (!RuleType::exists((string) $json['type']) ? RuleType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
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
            'AdaptiveQuantization' => isset($json['adaptiveQuantization']) ? (!Av1AdaptiveQuantization::exists((string) $json['adaptiveQuantization']) ? Av1AdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['adaptiveQuantization']) : null,
            'BitDepth' => isset($json['bitDepth']) ? (!Av1BitDepth::exists((string) $json['bitDepth']) ? Av1BitDepth::UNKNOWN_TO_SDK : (string) $json['bitDepth']) : null,
            'FilmGrainSynthesis' => isset($json['filmGrainSynthesis']) ? (!Av1FilmGrainSynthesis::exists((string) $json['filmGrainSynthesis']) ? Av1FilmGrainSynthesis::UNKNOWN_TO_SDK : (string) $json['filmGrainSynthesis']) : null,
            'FramerateControl' => isset($json['framerateControl']) ? (!Av1FramerateControl::exists((string) $json['framerateControl']) ? Av1FramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!Av1FramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? Av1FramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'NumberBFramesBetweenReferenceFrames' => isset($json['numberBFramesBetweenReferenceFrames']) ? (int) $json['numberBFramesBetweenReferenceFrames'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'QvbrSettings' => empty($json['qvbrSettings']) ? null : $this->populateResultAv1QvbrSettings($json['qvbrSettings']),
            'RateControlMode' => isset($json['rateControlMode']) ? (!Av1RateControlMode::exists((string) $json['rateControlMode']) ? Av1RateControlMode::UNKNOWN_TO_SDK : (string) $json['rateControlMode']) : null,
            'Slices' => isset($json['slices']) ? (int) $json['slices'] : null,
            'SpatialAdaptiveQuantization' => isset($json['spatialAdaptiveQuantization']) ? (!Av1SpatialAdaptiveQuantization::exists((string) $json['spatialAdaptiveQuantization']) ? Av1SpatialAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['spatialAdaptiveQuantization']) : null,
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
            'AvcIntraClass' => isset($json['avcIntraClass']) ? (!AvcIntraClass::exists((string) $json['avcIntraClass']) ? AvcIntraClass::UNKNOWN_TO_SDK : (string) $json['avcIntraClass']) : null,
            'AvcIntraUhdSettings' => empty($json['avcIntraUhdSettings']) ? null : $this->populateResultAvcIntraUhdSettings($json['avcIntraUhdSettings']),
            'FramerateControl' => isset($json['framerateControl']) ? (!AvcIntraFramerateControl::exists((string) $json['framerateControl']) ? AvcIntraFramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!AvcIntraFramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? AvcIntraFramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (!AvcIntraInterlaceMode::exists((string) $json['interlaceMode']) ? AvcIntraInterlaceMode::UNKNOWN_TO_SDK : (string) $json['interlaceMode']) : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (!AvcIntraScanTypeConversionMode::exists((string) $json['scanTypeConversionMode']) ? AvcIntraScanTypeConversionMode::UNKNOWN_TO_SDK : (string) $json['scanTypeConversionMode']) : null,
            'SlowPal' => isset($json['slowPal']) ? (!AvcIntraSlowPal::exists((string) $json['slowPal']) ? AvcIntraSlowPal::UNKNOWN_TO_SDK : (string) $json['slowPal']) : null,
            'Telecine' => isset($json['telecine']) ? (!AvcIntraTelecine::exists((string) $json['telecine']) ? AvcIntraTelecine::UNKNOWN_TO_SDK : (string) $json['telecine']) : null,
        ]);
    }

    private function populateResultAvcIntraUhdSettings(array $json): AvcIntraUhdSettings
    {
        return new AvcIntraUhdSettings([
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (!AvcIntraUhdQualityTuningLevel::exists((string) $json['qualityTuningLevel']) ? AvcIntraUhdQualityTuningLevel::UNKNOWN_TO_SDK : (string) $json['qualityTuningLevel']) : null,
        ]);
    }

    private function populateResultBandwidthReductionFilter(array $json): BandwidthReductionFilter
    {
        return new BandwidthReductionFilter([
            'Sharpening' => isset($json['sharpening']) ? (!BandwidthReductionFilterSharpening::exists((string) $json['sharpening']) ? BandwidthReductionFilterSharpening::UNKNOWN_TO_SDK : (string) $json['sharpening']) : null,
            'Strength' => isset($json['strength']) ? (!BandwidthReductionFilterStrength::exists((string) $json['strength']) ? BandwidthReductionFilterStrength::UNKNOWN_TO_SDK : (string) $json['strength']) : null,
        ]);
    }

    private function populateResultBurninDestinationSettings(array $json): BurninDestinationSettings
    {
        return new BurninDestinationSettings([
            'Alignment' => isset($json['alignment']) ? (!BurninSubtitleAlignment::exists((string) $json['alignment']) ? BurninSubtitleAlignment::UNKNOWN_TO_SDK : (string) $json['alignment']) : null,
            'ApplyFontColor' => isset($json['applyFontColor']) ? (!BurninSubtitleApplyFontColor::exists((string) $json['applyFontColor']) ? BurninSubtitleApplyFontColor::UNKNOWN_TO_SDK : (string) $json['applyFontColor']) : null,
            'BackgroundColor' => isset($json['backgroundColor']) ? (!BurninSubtitleBackgroundColor::exists((string) $json['backgroundColor']) ? BurninSubtitleBackgroundColor::UNKNOWN_TO_SDK : (string) $json['backgroundColor']) : null,
            'BackgroundOpacity' => isset($json['backgroundOpacity']) ? (int) $json['backgroundOpacity'] : null,
            'FallbackFont' => isset($json['fallbackFont']) ? (!BurninSubtitleFallbackFont::exists((string) $json['fallbackFont']) ? BurninSubtitleFallbackFont::UNKNOWN_TO_SDK : (string) $json['fallbackFont']) : null,
            'FontColor' => isset($json['fontColor']) ? (!BurninSubtitleFontColor::exists((string) $json['fontColor']) ? BurninSubtitleFontColor::UNKNOWN_TO_SDK : (string) $json['fontColor']) : null,
            'FontFileBold' => isset($json['fontFileBold']) ? (string) $json['fontFileBold'] : null,
            'FontFileBoldItalic' => isset($json['fontFileBoldItalic']) ? (string) $json['fontFileBoldItalic'] : null,
            'FontFileItalic' => isset($json['fontFileItalic']) ? (string) $json['fontFileItalic'] : null,
            'FontFileRegular' => isset($json['fontFileRegular']) ? (string) $json['fontFileRegular'] : null,
            'FontOpacity' => isset($json['fontOpacity']) ? (int) $json['fontOpacity'] : null,
            'FontResolution' => isset($json['fontResolution']) ? (int) $json['fontResolution'] : null,
            'FontScript' => isset($json['fontScript']) ? (!FontScript::exists((string) $json['fontScript']) ? FontScript::UNKNOWN_TO_SDK : (string) $json['fontScript']) : null,
            'FontSize' => isset($json['fontSize']) ? (int) $json['fontSize'] : null,
            'HexFontColor' => isset($json['hexFontColor']) ? (string) $json['hexFontColor'] : null,
            'OutlineColor' => isset($json['outlineColor']) ? (!BurninSubtitleOutlineColor::exists((string) $json['outlineColor']) ? BurninSubtitleOutlineColor::UNKNOWN_TO_SDK : (string) $json['outlineColor']) : null,
            'OutlineSize' => isset($json['outlineSize']) ? (int) $json['outlineSize'] : null,
            'RemoveRubyReserveAttributes' => isset($json['removeRubyReserveAttributes']) ? (!RemoveRubyReserveAttributes::exists((string) $json['removeRubyReserveAttributes']) ? RemoveRubyReserveAttributes::UNKNOWN_TO_SDK : (string) $json['removeRubyReserveAttributes']) : null,
            'ShadowColor' => isset($json['shadowColor']) ? (!BurninSubtitleShadowColor::exists((string) $json['shadowColor']) ? BurninSubtitleShadowColor::UNKNOWN_TO_SDK : (string) $json['shadowColor']) : null,
            'ShadowOpacity' => isset($json['shadowOpacity']) ? (int) $json['shadowOpacity'] : null,
            'ShadowXOffset' => isset($json['shadowXOffset']) ? (int) $json['shadowXOffset'] : null,
            'ShadowYOffset' => isset($json['shadowYOffset']) ? (int) $json['shadowYOffset'] : null,
            'StylePassthrough' => isset($json['stylePassthrough']) ? (!BurnInSubtitleStylePassthrough::exists((string) $json['stylePassthrough']) ? BurnInSubtitleStylePassthrough::UNKNOWN_TO_SDK : (string) $json['stylePassthrough']) : null,
            'TeletextSpacing' => isset($json['teletextSpacing']) ? (!BurninSubtitleTeletextSpacing::exists((string) $json['teletextSpacing']) ? BurninSubtitleTeletextSpacing::UNKNOWN_TO_SDK : (string) $json['teletextSpacing']) : null,
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
            'LanguageCode' => isset($json['languageCode']) ? (!LanguageCode::exists((string) $json['languageCode']) ? LanguageCode::UNKNOWN_TO_SDK : (string) $json['languageCode']) : null,
            'LanguageDescription' => isset($json['languageDescription']) ? (string) $json['languageDescription'] : null,
        ]);
    }

    private function populateResultCaptionDestinationSettings(array $json): CaptionDestinationSettings
    {
        return new CaptionDestinationSettings([
            'BurninDestinationSettings' => empty($json['burninDestinationSettings']) ? null : $this->populateResultBurninDestinationSettings($json['burninDestinationSettings']),
            'DestinationType' => isset($json['destinationType']) ? (!CaptionDestinationType::exists((string) $json['destinationType']) ? CaptionDestinationType::UNKNOWN_TO_SDK : (string) $json['destinationType']) : null,
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
            'LanguageCode' => isset($json['languageCode']) ? (!LanguageCode::exists((string) $json['languageCode']) ? LanguageCode::UNKNOWN_TO_SDK : (string) $json['languageCode']) : null,
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
            'SourceType' => isset($json['sourceType']) ? (!CaptionSourceType::exists((string) $json['sourceType']) ? CaptionSourceType::UNKNOWN_TO_SDK : (string) $json['sourceType']) : null,
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
            'EncryptionMethod' => isset($json['encryptionMethod']) ? (!CmafEncryptionType::exists((string) $json['encryptionMethod']) ? CmafEncryptionType::UNKNOWN_TO_SDK : (string) $json['encryptionMethod']) : null,
            'InitializationVectorInManifest' => isset($json['initializationVectorInManifest']) ? (!CmafInitializationVectorInManifest::exists((string) $json['initializationVectorInManifest']) ? CmafInitializationVectorInManifest::UNKNOWN_TO_SDK : (string) $json['initializationVectorInManifest']) : null,
            'SpekeKeyProvider' => empty($json['spekeKeyProvider']) ? null : $this->populateResultSpekeKeyProviderCmaf($json['spekeKeyProvider']),
            'StaticKeyProvider' => empty($json['staticKeyProvider']) ? null : $this->populateResultStaticKeyProvider($json['staticKeyProvider']),
            'Type' => isset($json['type']) ? (!CmafKeyProviderType::exists((string) $json['type']) ? CmafKeyProviderType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
        ]);
    }

    private function populateResultCmafGroupSettings(array $json): CmafGroupSettings
    {
        return new CmafGroupSettings([
            'AdditionalManifests' => !isset($json['additionalManifests']) ? null : $this->populateResult__listOfCmafAdditionalManifest($json['additionalManifests']),
            'BaseUrl' => isset($json['baseUrl']) ? (string) $json['baseUrl'] : null,
            'ClientCache' => isset($json['clientCache']) ? (!CmafClientCache::exists((string) $json['clientCache']) ? CmafClientCache::UNKNOWN_TO_SDK : (string) $json['clientCache']) : null,
            'CodecSpecification' => isset($json['codecSpecification']) ? (!CmafCodecSpecification::exists((string) $json['codecSpecification']) ? CmafCodecSpecification::UNKNOWN_TO_SDK : (string) $json['codecSpecification']) : null,
            'DashIFrameTrickPlayNameModifier' => isset($json['dashIFrameTrickPlayNameModifier']) ? (string) $json['dashIFrameTrickPlayNameModifier'] : null,
            'DashManifestStyle' => isset($json['dashManifestStyle']) ? (!DashManifestStyle::exists((string) $json['dashManifestStyle']) ? DashManifestStyle::UNKNOWN_TO_SDK : (string) $json['dashManifestStyle']) : null,
            'Destination' => isset($json['destination']) ? (string) $json['destination'] : null,
            'DestinationSettings' => empty($json['destinationSettings']) ? null : $this->populateResultDestinationSettings($json['destinationSettings']),
            'Encryption' => empty($json['encryption']) ? null : $this->populateResultCmafEncryptionSettings($json['encryption']),
            'FragmentLength' => isset($json['fragmentLength']) ? (int) $json['fragmentLength'] : null,
            'ImageBasedTrickPlay' => isset($json['imageBasedTrickPlay']) ? (!CmafImageBasedTrickPlay::exists((string) $json['imageBasedTrickPlay']) ? CmafImageBasedTrickPlay::UNKNOWN_TO_SDK : (string) $json['imageBasedTrickPlay']) : null,
            'ImageBasedTrickPlaySettings' => empty($json['imageBasedTrickPlaySettings']) ? null : $this->populateResultCmafImageBasedTrickPlaySettings($json['imageBasedTrickPlaySettings']),
            'ManifestCompression' => isset($json['manifestCompression']) ? (!CmafManifestCompression::exists((string) $json['manifestCompression']) ? CmafManifestCompression::UNKNOWN_TO_SDK : (string) $json['manifestCompression']) : null,
            'ManifestDurationFormat' => isset($json['manifestDurationFormat']) ? (!CmafManifestDurationFormat::exists((string) $json['manifestDurationFormat']) ? CmafManifestDurationFormat::UNKNOWN_TO_SDK : (string) $json['manifestDurationFormat']) : null,
            'MinBufferTime' => isset($json['minBufferTime']) ? (int) $json['minBufferTime'] : null,
            'MinFinalSegmentLength' => isset($json['minFinalSegmentLength']) ? (float) $json['minFinalSegmentLength'] : null,
            'MpdManifestBandwidthType' => isset($json['mpdManifestBandwidthType']) ? (!CmafMpdManifestBandwidthType::exists((string) $json['mpdManifestBandwidthType']) ? CmafMpdManifestBandwidthType::UNKNOWN_TO_SDK : (string) $json['mpdManifestBandwidthType']) : null,
            'MpdProfile' => isset($json['mpdProfile']) ? (!CmafMpdProfile::exists((string) $json['mpdProfile']) ? CmafMpdProfile::UNKNOWN_TO_SDK : (string) $json['mpdProfile']) : null,
            'PtsOffsetHandlingForBFrames' => isset($json['ptsOffsetHandlingForBFrames']) ? (!CmafPtsOffsetHandlingForBFrames::exists((string) $json['ptsOffsetHandlingForBFrames']) ? CmafPtsOffsetHandlingForBFrames::UNKNOWN_TO_SDK : (string) $json['ptsOffsetHandlingForBFrames']) : null,
            'SegmentControl' => isset($json['segmentControl']) ? (!CmafSegmentControl::exists((string) $json['segmentControl']) ? CmafSegmentControl::UNKNOWN_TO_SDK : (string) $json['segmentControl']) : null,
            'SegmentLength' => isset($json['segmentLength']) ? (int) $json['segmentLength'] : null,
            'SegmentLengthControl' => isset($json['segmentLengthControl']) ? (!CmafSegmentLengthControl::exists((string) $json['segmentLengthControl']) ? CmafSegmentLengthControl::UNKNOWN_TO_SDK : (string) $json['segmentLengthControl']) : null,
            'StreamInfResolution' => isset($json['streamInfResolution']) ? (!CmafStreamInfResolution::exists((string) $json['streamInfResolution']) ? CmafStreamInfResolution::UNKNOWN_TO_SDK : (string) $json['streamInfResolution']) : null,
            'TargetDurationCompatibilityMode' => isset($json['targetDurationCompatibilityMode']) ? (!CmafTargetDurationCompatibilityMode::exists((string) $json['targetDurationCompatibilityMode']) ? CmafTargetDurationCompatibilityMode::UNKNOWN_TO_SDK : (string) $json['targetDurationCompatibilityMode']) : null,
            'VideoCompositionOffsets' => isset($json['videoCompositionOffsets']) ? (!CmafVideoCompositionOffsets::exists((string) $json['videoCompositionOffsets']) ? CmafVideoCompositionOffsets::UNKNOWN_TO_SDK : (string) $json['videoCompositionOffsets']) : null,
            'WriteDashManifest' => isset($json['writeDashManifest']) ? (!CmafWriteDASHManifest::exists((string) $json['writeDashManifest']) ? CmafWriteDASHManifest::UNKNOWN_TO_SDK : (string) $json['writeDashManifest']) : null,
            'WriteHlsManifest' => isset($json['writeHlsManifest']) ? (!CmafWriteHLSManifest::exists((string) $json['writeHlsManifest']) ? CmafWriteHLSManifest::UNKNOWN_TO_SDK : (string) $json['writeHlsManifest']) : null,
            'WriteSegmentTimelineInRepresentation' => isset($json['writeSegmentTimelineInRepresentation']) ? (!CmafWriteSegmentTimelineInRepresentation::exists((string) $json['writeSegmentTimelineInRepresentation']) ? CmafWriteSegmentTimelineInRepresentation::UNKNOWN_TO_SDK : (string) $json['writeSegmentTimelineInRepresentation']) : null,
        ]);
    }

    private function populateResultCmafImageBasedTrickPlaySettings(array $json): CmafImageBasedTrickPlaySettings
    {
        return new CmafImageBasedTrickPlaySettings([
            'IntervalCadence' => isset($json['intervalCadence']) ? (!CmafIntervalCadence::exists((string) $json['intervalCadence']) ? CmafIntervalCadence::UNKNOWN_TO_SDK : (string) $json['intervalCadence']) : null,
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
            'AudioDuration' => isset($json['audioDuration']) ? (!CmfcAudioDuration::exists((string) $json['audioDuration']) ? CmfcAudioDuration::UNKNOWN_TO_SDK : (string) $json['audioDuration']) : null,
            'AudioGroupId' => isset($json['audioGroupId']) ? (string) $json['audioGroupId'] : null,
            'AudioRenditionSets' => isset($json['audioRenditionSets']) ? (string) $json['audioRenditionSets'] : null,
            'AudioTrackType' => isset($json['audioTrackType']) ? (!CmfcAudioTrackType::exists((string) $json['audioTrackType']) ? CmfcAudioTrackType::UNKNOWN_TO_SDK : (string) $json['audioTrackType']) : null,
            'C2paManifest' => isset($json['c2paManifest']) ? (!CmfcC2paManifest::exists((string) $json['c2paManifest']) ? CmfcC2paManifest::UNKNOWN_TO_SDK : (string) $json['c2paManifest']) : null,
            'CertificateSecret' => isset($json['certificateSecret']) ? (string) $json['certificateSecret'] : null,
            'DescriptiveVideoServiceFlag' => isset($json['descriptiveVideoServiceFlag']) ? (!CmfcDescriptiveVideoServiceFlag::exists((string) $json['descriptiveVideoServiceFlag']) ? CmfcDescriptiveVideoServiceFlag::UNKNOWN_TO_SDK : (string) $json['descriptiveVideoServiceFlag']) : null,
            'IFrameOnlyManifest' => isset($json['iFrameOnlyManifest']) ? (!CmfcIFrameOnlyManifest::exists((string) $json['iFrameOnlyManifest']) ? CmfcIFrameOnlyManifest::UNKNOWN_TO_SDK : (string) $json['iFrameOnlyManifest']) : null,
            'KlvMetadata' => isset($json['klvMetadata']) ? (!CmfcKlvMetadata::exists((string) $json['klvMetadata']) ? CmfcKlvMetadata::UNKNOWN_TO_SDK : (string) $json['klvMetadata']) : null,
            'ManifestMetadataSignaling' => isset($json['manifestMetadataSignaling']) ? (!CmfcManifestMetadataSignaling::exists((string) $json['manifestMetadataSignaling']) ? CmfcManifestMetadataSignaling::UNKNOWN_TO_SDK : (string) $json['manifestMetadataSignaling']) : null,
            'Scte35Esam' => isset($json['scte35Esam']) ? (!CmfcScte35Esam::exists((string) $json['scte35Esam']) ? CmfcScte35Esam::UNKNOWN_TO_SDK : (string) $json['scte35Esam']) : null,
            'Scte35Source' => isset($json['scte35Source']) ? (!CmfcScte35Source::exists((string) $json['scte35Source']) ? CmfcScte35Source::UNKNOWN_TO_SDK : (string) $json['scte35Source']) : null,
            'SigningKmsKey' => isset($json['signingKmsKey']) ? (string) $json['signingKmsKey'] : null,
            'TimedMetadata' => isset($json['timedMetadata']) ? (!CmfcTimedMetadata::exists((string) $json['timedMetadata']) ? CmfcTimedMetadata::UNKNOWN_TO_SDK : (string) $json['timedMetadata']) : null,
            'TimedMetadataBoxVersion' => isset($json['timedMetadataBoxVersion']) ? (!CmfcTimedMetadataBoxVersion::exists((string) $json['timedMetadataBoxVersion']) ? CmfcTimedMetadataBoxVersion::UNKNOWN_TO_SDK : (string) $json['timedMetadataBoxVersion']) : null,
            'TimedMetadataSchemeIdUri' => isset($json['timedMetadataSchemeIdUri']) ? (string) $json['timedMetadataSchemeIdUri'] : null,
            'TimedMetadataValue' => isset($json['timedMetadataValue']) ? (string) $json['timedMetadataValue'] : null,
        ]);
    }

    private function populateResultColorConversion3DLUTSetting(array $json): ColorConversion3DLUTSetting
    {
        return new ColorConversion3DLUTSetting([
            'FileInput' => isset($json['fileInput']) ? (string) $json['fileInput'] : null,
            'InputColorSpace' => isset($json['inputColorSpace']) ? (!ColorSpace::exists((string) $json['inputColorSpace']) ? ColorSpace::UNKNOWN_TO_SDK : (string) $json['inputColorSpace']) : null,
            'InputMasteringLuminance' => isset($json['inputMasteringLuminance']) ? (int) $json['inputMasteringLuminance'] : null,
            'OutputColorSpace' => isset($json['outputColorSpace']) ? (!ColorSpace::exists((string) $json['outputColorSpace']) ? ColorSpace::UNKNOWN_TO_SDK : (string) $json['outputColorSpace']) : null,
            'OutputMasteringLuminance' => isset($json['outputMasteringLuminance']) ? (int) $json['outputMasteringLuminance'] : null,
        ]);
    }

    private function populateResultColorCorrector(array $json): ColorCorrector
    {
        return new ColorCorrector([
            'Brightness' => isset($json['brightness']) ? (int) $json['brightness'] : null,
            'ClipLimits' => empty($json['clipLimits']) ? null : $this->populateResultClipLimits($json['clipLimits']),
            'ColorSpaceConversion' => isset($json['colorSpaceConversion']) ? (!ColorSpaceConversion::exists((string) $json['colorSpaceConversion']) ? ColorSpaceConversion::UNKNOWN_TO_SDK : (string) $json['colorSpaceConversion']) : null,
            'Contrast' => isset($json['contrast']) ? (int) $json['contrast'] : null,
            'Hdr10Metadata' => empty($json['hdr10Metadata']) ? null : $this->populateResultHdr10Metadata($json['hdr10Metadata']),
            'HdrToSdrToneMapper' => isset($json['hdrToSdrToneMapper']) ? (!HDRToSDRToneMapper::exists((string) $json['hdrToSdrToneMapper']) ? HDRToSDRToneMapper::UNKNOWN_TO_SDK : (string) $json['hdrToSdrToneMapper']) : null,
            'Hue' => isset($json['hue']) ? (int) $json['hue'] : null,
            'MaxLuminance' => isset($json['maxLuminance']) ? (int) $json['maxLuminance'] : null,
            'SampleRangeConversion' => isset($json['sampleRangeConversion']) ? (!SampleRangeConversion::exists((string) $json['sampleRangeConversion']) ? SampleRangeConversion::UNKNOWN_TO_SDK : (string) $json['sampleRangeConversion']) : null,
            'Saturation' => isset($json['saturation']) ? (int) $json['saturation'] : null,
            'SdrReferenceWhiteLevel' => isset($json['sdrReferenceWhiteLevel']) ? (int) $json['sdrReferenceWhiteLevel'] : null,
        ]);
    }

    private function populateResultContainerSettings(array $json): ContainerSettings
    {
        return new ContainerSettings([
            'CmfcSettings' => empty($json['cmfcSettings']) ? null : $this->populateResultCmfcSettings($json['cmfcSettings']),
            'Container' => isset($json['container']) ? (!ContainerType::exists((string) $json['container']) ? ContainerType::UNKNOWN_TO_SDK : (string) $json['container']) : null,
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
            'PlaybackDeviceCompatibility' => isset($json['playbackDeviceCompatibility']) ? (!DashIsoPlaybackDeviceCompatibility::exists((string) $json['playbackDeviceCompatibility']) ? DashIsoPlaybackDeviceCompatibility::UNKNOWN_TO_SDK : (string) $json['playbackDeviceCompatibility']) : null,
            'SpekeKeyProvider' => empty($json['spekeKeyProvider']) ? null : $this->populateResultSpekeKeyProvider($json['spekeKeyProvider']),
        ]);
    }

    private function populateResultDashIsoGroupSettings(array $json): DashIsoGroupSettings
    {
        return new DashIsoGroupSettings([
            'AdditionalManifests' => !isset($json['additionalManifests']) ? null : $this->populateResult__listOfDashAdditionalManifest($json['additionalManifests']),
            'AudioChannelConfigSchemeIdUri' => isset($json['audioChannelConfigSchemeIdUri']) ? (!DashIsoGroupAudioChannelConfigSchemeIdUri::exists((string) $json['audioChannelConfigSchemeIdUri']) ? DashIsoGroupAudioChannelConfigSchemeIdUri::UNKNOWN_TO_SDK : (string) $json['audioChannelConfigSchemeIdUri']) : null,
            'BaseUrl' => isset($json['baseUrl']) ? (string) $json['baseUrl'] : null,
            'DashIFrameTrickPlayNameModifier' => isset($json['dashIFrameTrickPlayNameModifier']) ? (string) $json['dashIFrameTrickPlayNameModifier'] : null,
            'DashManifestStyle' => isset($json['dashManifestStyle']) ? (!DashManifestStyle::exists((string) $json['dashManifestStyle']) ? DashManifestStyle::UNKNOWN_TO_SDK : (string) $json['dashManifestStyle']) : null,
            'Destination' => isset($json['destination']) ? (string) $json['destination'] : null,
            'DestinationSettings' => empty($json['destinationSettings']) ? null : $this->populateResultDestinationSettings($json['destinationSettings']),
            'Encryption' => empty($json['encryption']) ? null : $this->populateResultDashIsoEncryptionSettings($json['encryption']),
            'FragmentLength' => isset($json['fragmentLength']) ? (int) $json['fragmentLength'] : null,
            'HbbtvCompliance' => isset($json['hbbtvCompliance']) ? (!DashIsoHbbtvCompliance::exists((string) $json['hbbtvCompliance']) ? DashIsoHbbtvCompliance::UNKNOWN_TO_SDK : (string) $json['hbbtvCompliance']) : null,
            'ImageBasedTrickPlay' => isset($json['imageBasedTrickPlay']) ? (!DashIsoImageBasedTrickPlay::exists((string) $json['imageBasedTrickPlay']) ? DashIsoImageBasedTrickPlay::UNKNOWN_TO_SDK : (string) $json['imageBasedTrickPlay']) : null,
            'ImageBasedTrickPlaySettings' => empty($json['imageBasedTrickPlaySettings']) ? null : $this->populateResultDashIsoImageBasedTrickPlaySettings($json['imageBasedTrickPlaySettings']),
            'MinBufferTime' => isset($json['minBufferTime']) ? (int) $json['minBufferTime'] : null,
            'MinFinalSegmentLength' => isset($json['minFinalSegmentLength']) ? (float) $json['minFinalSegmentLength'] : null,
            'MpdManifestBandwidthType' => isset($json['mpdManifestBandwidthType']) ? (!DashIsoMpdManifestBandwidthType::exists((string) $json['mpdManifestBandwidthType']) ? DashIsoMpdManifestBandwidthType::UNKNOWN_TO_SDK : (string) $json['mpdManifestBandwidthType']) : null,
            'MpdProfile' => isset($json['mpdProfile']) ? (!DashIsoMpdProfile::exists((string) $json['mpdProfile']) ? DashIsoMpdProfile::UNKNOWN_TO_SDK : (string) $json['mpdProfile']) : null,
            'PtsOffsetHandlingForBFrames' => isset($json['ptsOffsetHandlingForBFrames']) ? (!DashIsoPtsOffsetHandlingForBFrames::exists((string) $json['ptsOffsetHandlingForBFrames']) ? DashIsoPtsOffsetHandlingForBFrames::UNKNOWN_TO_SDK : (string) $json['ptsOffsetHandlingForBFrames']) : null,
            'SegmentControl' => isset($json['segmentControl']) ? (!DashIsoSegmentControl::exists((string) $json['segmentControl']) ? DashIsoSegmentControl::UNKNOWN_TO_SDK : (string) $json['segmentControl']) : null,
            'SegmentLength' => isset($json['segmentLength']) ? (int) $json['segmentLength'] : null,
            'SegmentLengthControl' => isset($json['segmentLengthControl']) ? (!DashIsoSegmentLengthControl::exists((string) $json['segmentLengthControl']) ? DashIsoSegmentLengthControl::UNKNOWN_TO_SDK : (string) $json['segmentLengthControl']) : null,
            'VideoCompositionOffsets' => isset($json['videoCompositionOffsets']) ? (!DashIsoVideoCompositionOffsets::exists((string) $json['videoCompositionOffsets']) ? DashIsoVideoCompositionOffsets::UNKNOWN_TO_SDK : (string) $json['videoCompositionOffsets']) : null,
            'WriteSegmentTimelineInRepresentation' => isset($json['writeSegmentTimelineInRepresentation']) ? (!DashIsoWriteSegmentTimelineInRepresentation::exists((string) $json['writeSegmentTimelineInRepresentation']) ? DashIsoWriteSegmentTimelineInRepresentation::UNKNOWN_TO_SDK : (string) $json['writeSegmentTimelineInRepresentation']) : null,
        ]);
    }

    private function populateResultDashIsoImageBasedTrickPlaySettings(array $json): DashIsoImageBasedTrickPlaySettings
    {
        return new DashIsoImageBasedTrickPlaySettings([
            'IntervalCadence' => isset($json['intervalCadence']) ? (!DashIsoIntervalCadence::exists((string) $json['intervalCadence']) ? DashIsoIntervalCadence::UNKNOWN_TO_SDK : (string) $json['intervalCadence']) : null,
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
            'Algorithm' => isset($json['algorithm']) ? (!DeinterlaceAlgorithm::exists((string) $json['algorithm']) ? DeinterlaceAlgorithm::UNKNOWN_TO_SDK : (string) $json['algorithm']) : null,
            'Control' => isset($json['control']) ? (!DeinterlacerControl::exists((string) $json['control']) ? DeinterlacerControl::UNKNOWN_TO_SDK : (string) $json['control']) : null,
            'Mode' => isset($json['mode']) ? (!DeinterlacerMode::exists((string) $json['mode']) ? DeinterlacerMode::UNKNOWN_TO_SDK : (string) $json['mode']) : null,
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
            'L6Mode' => isset($json['l6Mode']) ? (!DolbyVisionLevel6Mode::exists((string) $json['l6Mode']) ? DolbyVisionLevel6Mode::UNKNOWN_TO_SDK : (string) $json['l6Mode']) : null,
            'Mapping' => isset($json['mapping']) ? (!DolbyVisionMapping::exists((string) $json['mapping']) ? DolbyVisionMapping::UNKNOWN_TO_SDK : (string) $json['mapping']) : null,
            'Profile' => isset($json['profile']) ? (!DolbyVisionProfile::exists((string) $json['profile']) ? DolbyVisionProfile::UNKNOWN_TO_SDK : (string) $json['profile']) : null,
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
            'OutputSdt' => isset($json['outputSdt']) ? (!OutputSdt::exists((string) $json['outputSdt']) ? OutputSdt::UNKNOWN_TO_SDK : (string) $json['outputSdt']) : null,
            'SdtInterval' => isset($json['sdtInterval']) ? (int) $json['sdtInterval'] : null,
            'ServiceName' => isset($json['serviceName']) ? (string) $json['serviceName'] : null,
            'ServiceProviderName' => isset($json['serviceProviderName']) ? (string) $json['serviceProviderName'] : null,
        ]);
    }

    private function populateResultDvbSubDestinationSettings(array $json): DvbSubDestinationSettings
    {
        return new DvbSubDestinationSettings([
            'Alignment' => isset($json['alignment']) ? (!DvbSubtitleAlignment::exists((string) $json['alignment']) ? DvbSubtitleAlignment::UNKNOWN_TO_SDK : (string) $json['alignment']) : null,
            'ApplyFontColor' => isset($json['applyFontColor']) ? (!DvbSubtitleApplyFontColor::exists((string) $json['applyFontColor']) ? DvbSubtitleApplyFontColor::UNKNOWN_TO_SDK : (string) $json['applyFontColor']) : null,
            'BackgroundColor' => isset($json['backgroundColor']) ? (!DvbSubtitleBackgroundColor::exists((string) $json['backgroundColor']) ? DvbSubtitleBackgroundColor::UNKNOWN_TO_SDK : (string) $json['backgroundColor']) : null,
            'BackgroundOpacity' => isset($json['backgroundOpacity']) ? (int) $json['backgroundOpacity'] : null,
            'DdsHandling' => isset($json['ddsHandling']) ? (!DvbddsHandling::exists((string) $json['ddsHandling']) ? DvbddsHandling::UNKNOWN_TO_SDK : (string) $json['ddsHandling']) : null,
            'DdsXCoordinate' => isset($json['ddsXCoordinate']) ? (int) $json['ddsXCoordinate'] : null,
            'DdsYCoordinate' => isset($json['ddsYCoordinate']) ? (int) $json['ddsYCoordinate'] : null,
            'FallbackFont' => isset($json['fallbackFont']) ? (!DvbSubSubtitleFallbackFont::exists((string) $json['fallbackFont']) ? DvbSubSubtitleFallbackFont::UNKNOWN_TO_SDK : (string) $json['fallbackFont']) : null,
            'FontColor' => isset($json['fontColor']) ? (!DvbSubtitleFontColor::exists((string) $json['fontColor']) ? DvbSubtitleFontColor::UNKNOWN_TO_SDK : (string) $json['fontColor']) : null,
            'FontFileBold' => isset($json['fontFileBold']) ? (string) $json['fontFileBold'] : null,
            'FontFileBoldItalic' => isset($json['fontFileBoldItalic']) ? (string) $json['fontFileBoldItalic'] : null,
            'FontFileItalic' => isset($json['fontFileItalic']) ? (string) $json['fontFileItalic'] : null,
            'FontFileRegular' => isset($json['fontFileRegular']) ? (string) $json['fontFileRegular'] : null,
            'FontOpacity' => isset($json['fontOpacity']) ? (int) $json['fontOpacity'] : null,
            'FontResolution' => isset($json['fontResolution']) ? (int) $json['fontResolution'] : null,
            'FontScript' => isset($json['fontScript']) ? (!FontScript::exists((string) $json['fontScript']) ? FontScript::UNKNOWN_TO_SDK : (string) $json['fontScript']) : null,
            'FontSize' => isset($json['fontSize']) ? (int) $json['fontSize'] : null,
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'HexFontColor' => isset($json['hexFontColor']) ? (string) $json['hexFontColor'] : null,
            'OutlineColor' => isset($json['outlineColor']) ? (!DvbSubtitleOutlineColor::exists((string) $json['outlineColor']) ? DvbSubtitleOutlineColor::UNKNOWN_TO_SDK : (string) $json['outlineColor']) : null,
            'OutlineSize' => isset($json['outlineSize']) ? (int) $json['outlineSize'] : null,
            'ShadowColor' => isset($json['shadowColor']) ? (!DvbSubtitleShadowColor::exists((string) $json['shadowColor']) ? DvbSubtitleShadowColor::UNKNOWN_TO_SDK : (string) $json['shadowColor']) : null,
            'ShadowOpacity' => isset($json['shadowOpacity']) ? (int) $json['shadowOpacity'] : null,
            'ShadowXOffset' => isset($json['shadowXOffset']) ? (int) $json['shadowXOffset'] : null,
            'ShadowYOffset' => isset($json['shadowYOffset']) ? (int) $json['shadowYOffset'] : null,
            'StylePassthrough' => isset($json['stylePassthrough']) ? (!DvbSubtitleStylePassthrough::exists((string) $json['stylePassthrough']) ? DvbSubtitleStylePassthrough::UNKNOWN_TO_SDK : (string) $json['stylePassthrough']) : null,
            'SubtitlingType' => isset($json['subtitlingType']) ? (!DvbSubtitlingType::exists((string) $json['subtitlingType']) ? DvbSubtitlingType::UNKNOWN_TO_SDK : (string) $json['subtitlingType']) : null,
            'TeletextSpacing' => isset($json['teletextSpacing']) ? (!DvbSubtitleTeletextSpacing::exists((string) $json['teletextSpacing']) ? DvbSubtitleTeletextSpacing::UNKNOWN_TO_SDK : (string) $json['teletextSpacing']) : null,
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
            'AudioDurationCorrection' => isset($json['audioDurationCorrection']) ? (!AudioDurationCorrection::exists((string) $json['audioDurationCorrection']) ? AudioDurationCorrection::UNKNOWN_TO_SDK : (string) $json['audioDurationCorrection']) : null,
            'ExternalAudioFileInput' => isset($json['externalAudioFileInput']) ? (string) $json['externalAudioFileInput'] : null,
            'LanguageCode' => isset($json['languageCode']) ? (!LanguageCode::exists((string) $json['languageCode']) ? LanguageCode::UNKNOWN_TO_SDK : (string) $json['languageCode']) : null,
            'Offset' => isset($json['offset']) ? (int) $json['offset'] : null,
            'SelectorType' => isset($json['selectorType']) ? (!DynamicAudioSelectorType::exists((string) $json['selectorType']) ? DynamicAudioSelectorType::UNKNOWN_TO_SDK : (string) $json['selectorType']) : null,
        ]);
    }

    private function populateResultEac3AtmosSettings(array $json): Eac3AtmosSettings
    {
        return new Eac3AtmosSettings([
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'BitstreamMode' => isset($json['bitstreamMode']) ? (!Eac3AtmosBitstreamMode::exists((string) $json['bitstreamMode']) ? Eac3AtmosBitstreamMode::UNKNOWN_TO_SDK : (string) $json['bitstreamMode']) : null,
            'CodingMode' => isset($json['codingMode']) ? (!Eac3AtmosCodingMode::exists((string) $json['codingMode']) ? Eac3AtmosCodingMode::UNKNOWN_TO_SDK : (string) $json['codingMode']) : null,
            'DialogueIntelligence' => isset($json['dialogueIntelligence']) ? (!Eac3AtmosDialogueIntelligence::exists((string) $json['dialogueIntelligence']) ? Eac3AtmosDialogueIntelligence::UNKNOWN_TO_SDK : (string) $json['dialogueIntelligence']) : null,
            'DownmixControl' => isset($json['downmixControl']) ? (!Eac3AtmosDownmixControl::exists((string) $json['downmixControl']) ? Eac3AtmosDownmixControl::UNKNOWN_TO_SDK : (string) $json['downmixControl']) : null,
            'DynamicRangeCompressionLine' => isset($json['dynamicRangeCompressionLine']) ? (!Eac3AtmosDynamicRangeCompressionLine::exists((string) $json['dynamicRangeCompressionLine']) ? Eac3AtmosDynamicRangeCompressionLine::UNKNOWN_TO_SDK : (string) $json['dynamicRangeCompressionLine']) : null,
            'DynamicRangeCompressionRf' => isset($json['dynamicRangeCompressionRf']) ? (!Eac3AtmosDynamicRangeCompressionRf::exists((string) $json['dynamicRangeCompressionRf']) ? Eac3AtmosDynamicRangeCompressionRf::UNKNOWN_TO_SDK : (string) $json['dynamicRangeCompressionRf']) : null,
            'DynamicRangeControl' => isset($json['dynamicRangeControl']) ? (!Eac3AtmosDynamicRangeControl::exists((string) $json['dynamicRangeControl']) ? Eac3AtmosDynamicRangeControl::UNKNOWN_TO_SDK : (string) $json['dynamicRangeControl']) : null,
            'LoRoCenterMixLevel' => isset($json['loRoCenterMixLevel']) ? (float) $json['loRoCenterMixLevel'] : null,
            'LoRoSurroundMixLevel' => isset($json['loRoSurroundMixLevel']) ? (float) $json['loRoSurroundMixLevel'] : null,
            'LtRtCenterMixLevel' => isset($json['ltRtCenterMixLevel']) ? (float) $json['ltRtCenterMixLevel'] : null,
            'LtRtSurroundMixLevel' => isset($json['ltRtSurroundMixLevel']) ? (float) $json['ltRtSurroundMixLevel'] : null,
            'MeteringMode' => isset($json['meteringMode']) ? (!Eac3AtmosMeteringMode::exists((string) $json['meteringMode']) ? Eac3AtmosMeteringMode::UNKNOWN_TO_SDK : (string) $json['meteringMode']) : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
            'SpeechThreshold' => isset($json['speechThreshold']) ? (int) $json['speechThreshold'] : null,
            'StereoDownmix' => isset($json['stereoDownmix']) ? (!Eac3AtmosStereoDownmix::exists((string) $json['stereoDownmix']) ? Eac3AtmosStereoDownmix::UNKNOWN_TO_SDK : (string) $json['stereoDownmix']) : null,
            'SurroundExMode' => isset($json['surroundExMode']) ? (!Eac3AtmosSurroundExMode::exists((string) $json['surroundExMode']) ? Eac3AtmosSurroundExMode::UNKNOWN_TO_SDK : (string) $json['surroundExMode']) : null,
        ]);
    }

    private function populateResultEac3Settings(array $json): Eac3Settings
    {
        return new Eac3Settings([
            'AttenuationControl' => isset($json['attenuationControl']) ? (!Eac3AttenuationControl::exists((string) $json['attenuationControl']) ? Eac3AttenuationControl::UNKNOWN_TO_SDK : (string) $json['attenuationControl']) : null,
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'BitstreamMode' => isset($json['bitstreamMode']) ? (!Eac3BitstreamMode::exists((string) $json['bitstreamMode']) ? Eac3BitstreamMode::UNKNOWN_TO_SDK : (string) $json['bitstreamMode']) : null,
            'CodingMode' => isset($json['codingMode']) ? (!Eac3CodingMode::exists((string) $json['codingMode']) ? Eac3CodingMode::UNKNOWN_TO_SDK : (string) $json['codingMode']) : null,
            'DcFilter' => isset($json['dcFilter']) ? (!Eac3DcFilter::exists((string) $json['dcFilter']) ? Eac3DcFilter::UNKNOWN_TO_SDK : (string) $json['dcFilter']) : null,
            'Dialnorm' => isset($json['dialnorm']) ? (int) $json['dialnorm'] : null,
            'DynamicRangeCompressionLine' => isset($json['dynamicRangeCompressionLine']) ? (!Eac3DynamicRangeCompressionLine::exists((string) $json['dynamicRangeCompressionLine']) ? Eac3DynamicRangeCompressionLine::UNKNOWN_TO_SDK : (string) $json['dynamicRangeCompressionLine']) : null,
            'DynamicRangeCompressionRf' => isset($json['dynamicRangeCompressionRf']) ? (!Eac3DynamicRangeCompressionRf::exists((string) $json['dynamicRangeCompressionRf']) ? Eac3DynamicRangeCompressionRf::UNKNOWN_TO_SDK : (string) $json['dynamicRangeCompressionRf']) : null,
            'LfeControl' => isset($json['lfeControl']) ? (!Eac3LfeControl::exists((string) $json['lfeControl']) ? Eac3LfeControl::UNKNOWN_TO_SDK : (string) $json['lfeControl']) : null,
            'LfeFilter' => isset($json['lfeFilter']) ? (!Eac3LfeFilter::exists((string) $json['lfeFilter']) ? Eac3LfeFilter::UNKNOWN_TO_SDK : (string) $json['lfeFilter']) : null,
            'LoRoCenterMixLevel' => isset($json['loRoCenterMixLevel']) ? (float) $json['loRoCenterMixLevel'] : null,
            'LoRoSurroundMixLevel' => isset($json['loRoSurroundMixLevel']) ? (float) $json['loRoSurroundMixLevel'] : null,
            'LtRtCenterMixLevel' => isset($json['ltRtCenterMixLevel']) ? (float) $json['ltRtCenterMixLevel'] : null,
            'LtRtSurroundMixLevel' => isset($json['ltRtSurroundMixLevel']) ? (float) $json['ltRtSurroundMixLevel'] : null,
            'MetadataControl' => isset($json['metadataControl']) ? (!Eac3MetadataControl::exists((string) $json['metadataControl']) ? Eac3MetadataControl::UNKNOWN_TO_SDK : (string) $json['metadataControl']) : null,
            'PassthroughControl' => isset($json['passthroughControl']) ? (!Eac3PassthroughControl::exists((string) $json['passthroughControl']) ? Eac3PassthroughControl::UNKNOWN_TO_SDK : (string) $json['passthroughControl']) : null,
            'PhaseControl' => isset($json['phaseControl']) ? (!Eac3PhaseControl::exists((string) $json['phaseControl']) ? Eac3PhaseControl::UNKNOWN_TO_SDK : (string) $json['phaseControl']) : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
            'StereoDownmix' => isset($json['stereoDownmix']) ? (!Eac3StereoDownmix::exists((string) $json['stereoDownmix']) ? Eac3StereoDownmix::UNKNOWN_TO_SDK : (string) $json['stereoDownmix']) : null,
            'SurroundExMode' => isset($json['surroundExMode']) ? (!Eac3SurroundExMode::exists((string) $json['surroundExMode']) ? Eac3SurroundExMode::UNKNOWN_TO_SDK : (string) $json['surroundExMode']) : null,
            'SurroundMode' => isset($json['surroundMode']) ? (!Eac3SurroundMode::exists((string) $json['surroundMode']) ? Eac3SurroundMode::UNKNOWN_TO_SDK : (string) $json['surroundMode']) : null,
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
            'Convert608To708' => isset($json['convert608To708']) ? (!EmbeddedConvert608To708::exists((string) $json['convert608To708']) ? EmbeddedConvert608To708::UNKNOWN_TO_SDK : (string) $json['convert608To708']) : null,
            'Source608ChannelNumber' => isset($json['source608ChannelNumber']) ? (int) $json['source608ChannelNumber'] : null,
            'Source608TrackNumber' => isset($json['source608TrackNumber']) ? (int) $json['source608TrackNumber'] : null,
            'TerminateCaptions' => isset($json['terminateCaptions']) ? (!EmbeddedTerminateCaptions::exists((string) $json['terminateCaptions']) ? EmbeddedTerminateCaptions::UNKNOWN_TO_SDK : (string) $json['terminateCaptions']) : null,
        ]);
    }

    private function populateResultEncryptionContractConfiguration(array $json): EncryptionContractConfiguration
    {
        return new EncryptionContractConfiguration([
            'SpekeAudioPreset' => isset($json['spekeAudioPreset']) ? (!PresetSpeke20Audio::exists((string) $json['spekeAudioPreset']) ? PresetSpeke20Audio::UNKNOWN_TO_SDK : (string) $json['spekeAudioPreset']) : null,
            'SpekeVideoPreset' => isset($json['spekeVideoPreset']) ? (!PresetSpeke20Video::exists((string) $json['spekeVideoPreset']) ? PresetSpeke20Video::UNKNOWN_TO_SDK : (string) $json['spekeVideoPreset']) : null,
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
            'CopyProtectionAction' => isset($json['copyProtectionAction']) ? (!CopyProtectionAction::exists((string) $json['copyProtectionAction']) ? CopyProtectionAction::UNKNOWN_TO_SDK : (string) $json['copyProtectionAction']) : null,
            'VchipAction' => isset($json['vchipAction']) ? (!VchipAction::exists((string) $json['vchipAction']) ? VchipAction::UNKNOWN_TO_SDK : (string) $json['vchipAction']) : null,
        ]);
    }

    private function populateResultF4vSettings(array $json): F4vSettings
    {
        return new F4vSettings([
            'MoovPlacement' => isset($json['moovPlacement']) ? (!F4vMoovPlacement::exists((string) $json['moovPlacement']) ? F4vMoovPlacement::UNKNOWN_TO_SDK : (string) $json['moovPlacement']) : null,
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
            'ByteRateLimit' => isset($json['byteRateLimit']) ? (!CaptionSourceByteRateLimit::exists((string) $json['byteRateLimit']) ? CaptionSourceByteRateLimit::UNKNOWN_TO_SDK : (string) $json['byteRateLimit']) : null,
            'Convert608To708' => isset($json['convert608To708']) ? (!FileSourceConvert608To708::exists((string) $json['convert608To708']) ? FileSourceConvert608To708::UNKNOWN_TO_SDK : (string) $json['convert608To708']) : null,
            'ConvertPaintToPop' => isset($json['convertPaintToPop']) ? (!CaptionSourceConvertPaintOnToPopOn::exists((string) $json['convertPaintToPop']) ? CaptionSourceConvertPaintOnToPopOn::UNKNOWN_TO_SDK : (string) $json['convertPaintToPop']) : null,
            'Framerate' => empty($json['framerate']) ? null : $this->populateResultCaptionSourceFramerate($json['framerate']),
            'SourceFile' => isset($json['sourceFile']) ? (string) $json['sourceFile'] : null,
            'TimeDelta' => isset($json['timeDelta']) ? (int) $json['timeDelta'] : null,
            'TimeDeltaUnits' => isset($json['timeDeltaUnits']) ? (!FileSourceTimeDeltaUnits::exists((string) $json['timeDeltaUnits']) ? FileSourceTimeDeltaUnits::UNKNOWN_TO_SDK : (string) $json['timeDeltaUnits']) : null,
            'UpconvertSTLToTeletext' => isset($json['upconvertSTLToTeletext']) ? (!CaptionSourceUpconvertSTLToTeletext::exists((string) $json['upconvertSTLToTeletext']) ? CaptionSourceUpconvertSTLToTeletext::UNKNOWN_TO_SDK : (string) $json['upconvertSTLToTeletext']) : null,
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
            'FramerateControl' => isset($json['framerateControl']) ? (!GifFramerateControl::exists((string) $json['framerateControl']) ? GifFramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!GifFramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? GifFramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
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
            'AdaptiveQuantization' => isset($json['adaptiveQuantization']) ? (!H264AdaptiveQuantization::exists((string) $json['adaptiveQuantization']) ? H264AdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['adaptiveQuantization']) : null,
            'BandwidthReductionFilter' => empty($json['bandwidthReductionFilter']) ? null : $this->populateResultBandwidthReductionFilter($json['bandwidthReductionFilter']),
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'CodecLevel' => isset($json['codecLevel']) ? (!H264CodecLevel::exists((string) $json['codecLevel']) ? H264CodecLevel::UNKNOWN_TO_SDK : (string) $json['codecLevel']) : null,
            'CodecProfile' => isset($json['codecProfile']) ? (!H264CodecProfile::exists((string) $json['codecProfile']) ? H264CodecProfile::UNKNOWN_TO_SDK : (string) $json['codecProfile']) : null,
            'DynamicSubGop' => isset($json['dynamicSubGop']) ? (!H264DynamicSubGop::exists((string) $json['dynamicSubGop']) ? H264DynamicSubGop::UNKNOWN_TO_SDK : (string) $json['dynamicSubGop']) : null,
            'EndOfStreamMarkers' => isset($json['endOfStreamMarkers']) ? (!H264EndOfStreamMarkers::exists((string) $json['endOfStreamMarkers']) ? H264EndOfStreamMarkers::UNKNOWN_TO_SDK : (string) $json['endOfStreamMarkers']) : null,
            'EntropyEncoding' => isset($json['entropyEncoding']) ? (!H264EntropyEncoding::exists((string) $json['entropyEncoding']) ? H264EntropyEncoding::UNKNOWN_TO_SDK : (string) $json['entropyEncoding']) : null,
            'FieldEncoding' => isset($json['fieldEncoding']) ? (!H264FieldEncoding::exists((string) $json['fieldEncoding']) ? H264FieldEncoding::UNKNOWN_TO_SDK : (string) $json['fieldEncoding']) : null,
            'FlickerAdaptiveQuantization' => isset($json['flickerAdaptiveQuantization']) ? (!H264FlickerAdaptiveQuantization::exists((string) $json['flickerAdaptiveQuantization']) ? H264FlickerAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['flickerAdaptiveQuantization']) : null,
            'FramerateControl' => isset($json['framerateControl']) ? (!H264FramerateControl::exists((string) $json['framerateControl']) ? H264FramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!H264FramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? H264FramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopBReference' => isset($json['gopBReference']) ? (!H264GopBReference::exists((string) $json['gopBReference']) ? H264GopBReference::UNKNOWN_TO_SDK : (string) $json['gopBReference']) : null,
            'GopClosedCadence' => isset($json['gopClosedCadence']) ? (int) $json['gopClosedCadence'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'GopSizeUnits' => isset($json['gopSizeUnits']) ? (!H264GopSizeUnits::exists((string) $json['gopSizeUnits']) ? H264GopSizeUnits::UNKNOWN_TO_SDK : (string) $json['gopSizeUnits']) : null,
            'HrdBufferFinalFillPercentage' => isset($json['hrdBufferFinalFillPercentage']) ? (int) $json['hrdBufferFinalFillPercentage'] : null,
            'HrdBufferInitialFillPercentage' => isset($json['hrdBufferInitialFillPercentage']) ? (int) $json['hrdBufferInitialFillPercentage'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (!H264InterlaceMode::exists((string) $json['interlaceMode']) ? H264InterlaceMode::UNKNOWN_TO_SDK : (string) $json['interlaceMode']) : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'MinIInterval' => isset($json['minIInterval']) ? (int) $json['minIInterval'] : null,
            'NumberBFramesBetweenReferenceFrames' => isset($json['numberBFramesBetweenReferenceFrames']) ? (int) $json['numberBFramesBetweenReferenceFrames'] : null,
            'NumberReferenceFrames' => isset($json['numberReferenceFrames']) ? (int) $json['numberReferenceFrames'] : null,
            'ParControl' => isset($json['parControl']) ? (!H264ParControl::exists((string) $json['parControl']) ? H264ParControl::UNKNOWN_TO_SDK : (string) $json['parControl']) : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (!H264QualityTuningLevel::exists((string) $json['qualityTuningLevel']) ? H264QualityTuningLevel::UNKNOWN_TO_SDK : (string) $json['qualityTuningLevel']) : null,
            'QvbrSettings' => empty($json['qvbrSettings']) ? null : $this->populateResultH264QvbrSettings($json['qvbrSettings']),
            'RateControlMode' => isset($json['rateControlMode']) ? (!H264RateControlMode::exists((string) $json['rateControlMode']) ? H264RateControlMode::UNKNOWN_TO_SDK : (string) $json['rateControlMode']) : null,
            'RepeatPps' => isset($json['repeatPps']) ? (!H264RepeatPps::exists((string) $json['repeatPps']) ? H264RepeatPps::UNKNOWN_TO_SDK : (string) $json['repeatPps']) : null,
            'SaliencyAwareEncoding' => isset($json['saliencyAwareEncoding']) ? (!H264SaliencyAwareEncoding::exists((string) $json['saliencyAwareEncoding']) ? H264SaliencyAwareEncoding::UNKNOWN_TO_SDK : (string) $json['saliencyAwareEncoding']) : null,
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (!H264ScanTypeConversionMode::exists((string) $json['scanTypeConversionMode']) ? H264ScanTypeConversionMode::UNKNOWN_TO_SDK : (string) $json['scanTypeConversionMode']) : null,
            'SceneChangeDetect' => isset($json['sceneChangeDetect']) ? (!H264SceneChangeDetect::exists((string) $json['sceneChangeDetect']) ? H264SceneChangeDetect::UNKNOWN_TO_SDK : (string) $json['sceneChangeDetect']) : null,
            'Slices' => isset($json['slices']) ? (int) $json['slices'] : null,
            'SlowPal' => isset($json['slowPal']) ? (!H264SlowPal::exists((string) $json['slowPal']) ? H264SlowPal::UNKNOWN_TO_SDK : (string) $json['slowPal']) : null,
            'Softness' => isset($json['softness']) ? (int) $json['softness'] : null,
            'SpatialAdaptiveQuantization' => isset($json['spatialAdaptiveQuantization']) ? (!H264SpatialAdaptiveQuantization::exists((string) $json['spatialAdaptiveQuantization']) ? H264SpatialAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['spatialAdaptiveQuantization']) : null,
            'Syntax' => isset($json['syntax']) ? (!H264Syntax::exists((string) $json['syntax']) ? H264Syntax::UNKNOWN_TO_SDK : (string) $json['syntax']) : null,
            'Telecine' => isset($json['telecine']) ? (!H264Telecine::exists((string) $json['telecine']) ? H264Telecine::UNKNOWN_TO_SDK : (string) $json['telecine']) : null,
            'TemporalAdaptiveQuantization' => isset($json['temporalAdaptiveQuantization']) ? (!H264TemporalAdaptiveQuantization::exists((string) $json['temporalAdaptiveQuantization']) ? H264TemporalAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['temporalAdaptiveQuantization']) : null,
            'UnregisteredSeiTimecode' => isset($json['unregisteredSeiTimecode']) ? (!H264UnregisteredSeiTimecode::exists((string) $json['unregisteredSeiTimecode']) ? H264UnregisteredSeiTimecode::UNKNOWN_TO_SDK : (string) $json['unregisteredSeiTimecode']) : null,
            'WriteMp4PackagingType' => isset($json['writeMp4PackagingType']) ? (!H264WriteMp4PackagingType::exists((string) $json['writeMp4PackagingType']) ? H264WriteMp4PackagingType::UNKNOWN_TO_SDK : (string) $json['writeMp4PackagingType']) : null,
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
            'AdaptiveQuantization' => isset($json['adaptiveQuantization']) ? (!H265AdaptiveQuantization::exists((string) $json['adaptiveQuantization']) ? H265AdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['adaptiveQuantization']) : null,
            'AlternateTransferFunctionSei' => isset($json['alternateTransferFunctionSei']) ? (!H265AlternateTransferFunctionSei::exists((string) $json['alternateTransferFunctionSei']) ? H265AlternateTransferFunctionSei::UNKNOWN_TO_SDK : (string) $json['alternateTransferFunctionSei']) : null,
            'BandwidthReductionFilter' => empty($json['bandwidthReductionFilter']) ? null : $this->populateResultBandwidthReductionFilter($json['bandwidthReductionFilter']),
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'CodecLevel' => isset($json['codecLevel']) ? (!H265CodecLevel::exists((string) $json['codecLevel']) ? H265CodecLevel::UNKNOWN_TO_SDK : (string) $json['codecLevel']) : null,
            'CodecProfile' => isset($json['codecProfile']) ? (!H265CodecProfile::exists((string) $json['codecProfile']) ? H265CodecProfile::UNKNOWN_TO_SDK : (string) $json['codecProfile']) : null,
            'Deblocking' => isset($json['deblocking']) ? (!H265Deblocking::exists((string) $json['deblocking']) ? H265Deblocking::UNKNOWN_TO_SDK : (string) $json['deblocking']) : null,
            'DynamicSubGop' => isset($json['dynamicSubGop']) ? (!H265DynamicSubGop::exists((string) $json['dynamicSubGop']) ? H265DynamicSubGop::UNKNOWN_TO_SDK : (string) $json['dynamicSubGop']) : null,
            'EndOfStreamMarkers' => isset($json['endOfStreamMarkers']) ? (!H265EndOfStreamMarkers::exists((string) $json['endOfStreamMarkers']) ? H265EndOfStreamMarkers::UNKNOWN_TO_SDK : (string) $json['endOfStreamMarkers']) : null,
            'FlickerAdaptiveQuantization' => isset($json['flickerAdaptiveQuantization']) ? (!H265FlickerAdaptiveQuantization::exists((string) $json['flickerAdaptiveQuantization']) ? H265FlickerAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['flickerAdaptiveQuantization']) : null,
            'FramerateControl' => isset($json['framerateControl']) ? (!H265FramerateControl::exists((string) $json['framerateControl']) ? H265FramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!H265FramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? H265FramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopBReference' => isset($json['gopBReference']) ? (!H265GopBReference::exists((string) $json['gopBReference']) ? H265GopBReference::UNKNOWN_TO_SDK : (string) $json['gopBReference']) : null,
            'GopClosedCadence' => isset($json['gopClosedCadence']) ? (int) $json['gopClosedCadence'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'GopSizeUnits' => isset($json['gopSizeUnits']) ? (!H265GopSizeUnits::exists((string) $json['gopSizeUnits']) ? H265GopSizeUnits::UNKNOWN_TO_SDK : (string) $json['gopSizeUnits']) : null,
            'HrdBufferFinalFillPercentage' => isset($json['hrdBufferFinalFillPercentage']) ? (int) $json['hrdBufferFinalFillPercentage'] : null,
            'HrdBufferInitialFillPercentage' => isset($json['hrdBufferInitialFillPercentage']) ? (int) $json['hrdBufferInitialFillPercentage'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (!H265InterlaceMode::exists((string) $json['interlaceMode']) ? H265InterlaceMode::UNKNOWN_TO_SDK : (string) $json['interlaceMode']) : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'MinIInterval' => isset($json['minIInterval']) ? (int) $json['minIInterval'] : null,
            'MvOverPictureBoundaries' => isset($json['mvOverPictureBoundaries']) ? (!H265MvOverPictureBoundaries::exists((string) $json['mvOverPictureBoundaries']) ? H265MvOverPictureBoundaries::UNKNOWN_TO_SDK : (string) $json['mvOverPictureBoundaries']) : null,
            'MvTemporalPredictor' => isset($json['mvTemporalPredictor']) ? (!H265MvTemporalPredictor::exists((string) $json['mvTemporalPredictor']) ? H265MvTemporalPredictor::UNKNOWN_TO_SDK : (string) $json['mvTemporalPredictor']) : null,
            'NumberBFramesBetweenReferenceFrames' => isset($json['numberBFramesBetweenReferenceFrames']) ? (int) $json['numberBFramesBetweenReferenceFrames'] : null,
            'NumberReferenceFrames' => isset($json['numberReferenceFrames']) ? (int) $json['numberReferenceFrames'] : null,
            'ParControl' => isset($json['parControl']) ? (!H265ParControl::exists((string) $json['parControl']) ? H265ParControl::UNKNOWN_TO_SDK : (string) $json['parControl']) : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (!H265QualityTuningLevel::exists((string) $json['qualityTuningLevel']) ? H265QualityTuningLevel::UNKNOWN_TO_SDK : (string) $json['qualityTuningLevel']) : null,
            'QvbrSettings' => empty($json['qvbrSettings']) ? null : $this->populateResultH265QvbrSettings($json['qvbrSettings']),
            'RateControlMode' => isset($json['rateControlMode']) ? (!H265RateControlMode::exists((string) $json['rateControlMode']) ? H265RateControlMode::UNKNOWN_TO_SDK : (string) $json['rateControlMode']) : null,
            'SampleAdaptiveOffsetFilterMode' => isset($json['sampleAdaptiveOffsetFilterMode']) ? (!H265SampleAdaptiveOffsetFilterMode::exists((string) $json['sampleAdaptiveOffsetFilterMode']) ? H265SampleAdaptiveOffsetFilterMode::UNKNOWN_TO_SDK : (string) $json['sampleAdaptiveOffsetFilterMode']) : null,
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (!H265ScanTypeConversionMode::exists((string) $json['scanTypeConversionMode']) ? H265ScanTypeConversionMode::UNKNOWN_TO_SDK : (string) $json['scanTypeConversionMode']) : null,
            'SceneChangeDetect' => isset($json['sceneChangeDetect']) ? (!H265SceneChangeDetect::exists((string) $json['sceneChangeDetect']) ? H265SceneChangeDetect::UNKNOWN_TO_SDK : (string) $json['sceneChangeDetect']) : null,
            'Slices' => isset($json['slices']) ? (int) $json['slices'] : null,
            'SlowPal' => isset($json['slowPal']) ? (!H265SlowPal::exists((string) $json['slowPal']) ? H265SlowPal::UNKNOWN_TO_SDK : (string) $json['slowPal']) : null,
            'SpatialAdaptiveQuantization' => isset($json['spatialAdaptiveQuantization']) ? (!H265SpatialAdaptiveQuantization::exists((string) $json['spatialAdaptiveQuantization']) ? H265SpatialAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['spatialAdaptiveQuantization']) : null,
            'Telecine' => isset($json['telecine']) ? (!H265Telecine::exists((string) $json['telecine']) ? H265Telecine::UNKNOWN_TO_SDK : (string) $json['telecine']) : null,
            'TemporalAdaptiveQuantization' => isset($json['temporalAdaptiveQuantization']) ? (!H265TemporalAdaptiveQuantization::exists((string) $json['temporalAdaptiveQuantization']) ? H265TemporalAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['temporalAdaptiveQuantization']) : null,
            'TemporalIds' => isset($json['temporalIds']) ? (!H265TemporalIds::exists((string) $json['temporalIds']) ? H265TemporalIds::UNKNOWN_TO_SDK : (string) $json['temporalIds']) : null,
            'TileHeight' => isset($json['tileHeight']) ? (int) $json['tileHeight'] : null,
            'TilePadding' => isset($json['tilePadding']) ? (!H265TilePadding::exists((string) $json['tilePadding']) ? H265TilePadding::UNKNOWN_TO_SDK : (string) $json['tilePadding']) : null,
            'TileWidth' => isset($json['tileWidth']) ? (int) $json['tileWidth'] : null,
            'Tiles' => isset($json['tiles']) ? (!H265Tiles::exists((string) $json['tiles']) ? H265Tiles::UNKNOWN_TO_SDK : (string) $json['tiles']) : null,
            'TreeBlockSize' => isset($json['treeBlockSize']) ? (!H265TreeBlockSize::exists((string) $json['treeBlockSize']) ? H265TreeBlockSize::UNKNOWN_TO_SDK : (string) $json['treeBlockSize']) : null,
            'UnregisteredSeiTimecode' => isset($json['unregisteredSeiTimecode']) ? (!H265UnregisteredSeiTimecode::exists((string) $json['unregisteredSeiTimecode']) ? H265UnregisteredSeiTimecode::UNKNOWN_TO_SDK : (string) $json['unregisteredSeiTimecode']) : null,
            'WriteMp4PackagingType' => isset($json['writeMp4PackagingType']) ? (!H265WriteMp4PackagingType::exists((string) $json['writeMp4PackagingType']) ? H265WriteMp4PackagingType::UNKNOWN_TO_SDK : (string) $json['writeMp4PackagingType']) : null,
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
            'LanguageCode' => isset($json['languageCode']) ? (!LanguageCode::exists((string) $json['languageCode']) ? LanguageCode::UNKNOWN_TO_SDK : (string) $json['languageCode']) : null,
            'LanguageDescription' => isset($json['languageDescription']) ? (string) $json['languageDescription'] : null,
        ]);
    }

    private function populateResultHlsEncryptionSettings(array $json): HlsEncryptionSettings
    {
        return new HlsEncryptionSettings([
            'ConstantInitializationVector' => isset($json['constantInitializationVector']) ? (string) $json['constantInitializationVector'] : null,
            'EncryptionMethod' => isset($json['encryptionMethod']) ? (!HlsEncryptionType::exists((string) $json['encryptionMethod']) ? HlsEncryptionType::UNKNOWN_TO_SDK : (string) $json['encryptionMethod']) : null,
            'InitializationVectorInManifest' => isset($json['initializationVectorInManifest']) ? (!HlsInitializationVectorInManifest::exists((string) $json['initializationVectorInManifest']) ? HlsInitializationVectorInManifest::UNKNOWN_TO_SDK : (string) $json['initializationVectorInManifest']) : null,
            'OfflineEncrypted' => isset($json['offlineEncrypted']) ? (!HlsOfflineEncrypted::exists((string) $json['offlineEncrypted']) ? HlsOfflineEncrypted::UNKNOWN_TO_SDK : (string) $json['offlineEncrypted']) : null,
            'SpekeKeyProvider' => empty($json['spekeKeyProvider']) ? null : $this->populateResultSpekeKeyProvider($json['spekeKeyProvider']),
            'StaticKeyProvider' => empty($json['staticKeyProvider']) ? null : $this->populateResultStaticKeyProvider($json['staticKeyProvider']),
            'Type' => isset($json['type']) ? (!HlsKeyProviderType::exists((string) $json['type']) ? HlsKeyProviderType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
        ]);
    }

    private function populateResultHlsGroupSettings(array $json): HlsGroupSettings
    {
        return new HlsGroupSettings([
            'AdMarkers' => !isset($json['adMarkers']) ? null : $this->populateResult__listOfHlsAdMarkers($json['adMarkers']),
            'AdditionalManifests' => !isset($json['additionalManifests']) ? null : $this->populateResult__listOfHlsAdditionalManifest($json['additionalManifests']),
            'AudioOnlyHeader' => isset($json['audioOnlyHeader']) ? (!HlsAudioOnlyHeader::exists((string) $json['audioOnlyHeader']) ? HlsAudioOnlyHeader::UNKNOWN_TO_SDK : (string) $json['audioOnlyHeader']) : null,
            'BaseUrl' => isset($json['baseUrl']) ? (string) $json['baseUrl'] : null,
            'CaptionLanguageMappings' => !isset($json['captionLanguageMappings']) ? null : $this->populateResult__listOfHlsCaptionLanguageMapping($json['captionLanguageMappings']),
            'CaptionLanguageSetting' => isset($json['captionLanguageSetting']) ? (!HlsCaptionLanguageSetting::exists((string) $json['captionLanguageSetting']) ? HlsCaptionLanguageSetting::UNKNOWN_TO_SDK : (string) $json['captionLanguageSetting']) : null,
            'CaptionSegmentLengthControl' => isset($json['captionSegmentLengthControl']) ? (!HlsCaptionSegmentLengthControl::exists((string) $json['captionSegmentLengthControl']) ? HlsCaptionSegmentLengthControl::UNKNOWN_TO_SDK : (string) $json['captionSegmentLengthControl']) : null,
            'ClientCache' => isset($json['clientCache']) ? (!HlsClientCache::exists((string) $json['clientCache']) ? HlsClientCache::UNKNOWN_TO_SDK : (string) $json['clientCache']) : null,
            'CodecSpecification' => isset($json['codecSpecification']) ? (!HlsCodecSpecification::exists((string) $json['codecSpecification']) ? HlsCodecSpecification::UNKNOWN_TO_SDK : (string) $json['codecSpecification']) : null,
            'Destination' => isset($json['destination']) ? (string) $json['destination'] : null,
            'DestinationSettings' => empty($json['destinationSettings']) ? null : $this->populateResultDestinationSettings($json['destinationSettings']),
            'DirectoryStructure' => isset($json['directoryStructure']) ? (!HlsDirectoryStructure::exists((string) $json['directoryStructure']) ? HlsDirectoryStructure::UNKNOWN_TO_SDK : (string) $json['directoryStructure']) : null,
            'Encryption' => empty($json['encryption']) ? null : $this->populateResultHlsEncryptionSettings($json['encryption']),
            'ImageBasedTrickPlay' => isset($json['imageBasedTrickPlay']) ? (!HlsImageBasedTrickPlay::exists((string) $json['imageBasedTrickPlay']) ? HlsImageBasedTrickPlay::UNKNOWN_TO_SDK : (string) $json['imageBasedTrickPlay']) : null,
            'ImageBasedTrickPlaySettings' => empty($json['imageBasedTrickPlaySettings']) ? null : $this->populateResultHlsImageBasedTrickPlaySettings($json['imageBasedTrickPlaySettings']),
            'ManifestCompression' => isset($json['manifestCompression']) ? (!HlsManifestCompression::exists((string) $json['manifestCompression']) ? HlsManifestCompression::UNKNOWN_TO_SDK : (string) $json['manifestCompression']) : null,
            'ManifestDurationFormat' => isset($json['manifestDurationFormat']) ? (!HlsManifestDurationFormat::exists((string) $json['manifestDurationFormat']) ? HlsManifestDurationFormat::UNKNOWN_TO_SDK : (string) $json['manifestDurationFormat']) : null,
            'MinFinalSegmentLength' => isset($json['minFinalSegmentLength']) ? (float) $json['minFinalSegmentLength'] : null,
            'MinSegmentLength' => isset($json['minSegmentLength']) ? (int) $json['minSegmentLength'] : null,
            'OutputSelection' => isset($json['outputSelection']) ? (!HlsOutputSelection::exists((string) $json['outputSelection']) ? HlsOutputSelection::UNKNOWN_TO_SDK : (string) $json['outputSelection']) : null,
            'ProgramDateTime' => isset($json['programDateTime']) ? (!HlsProgramDateTime::exists((string) $json['programDateTime']) ? HlsProgramDateTime::UNKNOWN_TO_SDK : (string) $json['programDateTime']) : null,
            'ProgramDateTimePeriod' => isset($json['programDateTimePeriod']) ? (int) $json['programDateTimePeriod'] : null,
            'ProgressiveWriteHlsManifest' => isset($json['progressiveWriteHlsManifest']) ? (!HlsProgressiveWriteHlsManifest::exists((string) $json['progressiveWriteHlsManifest']) ? HlsProgressiveWriteHlsManifest::UNKNOWN_TO_SDK : (string) $json['progressiveWriteHlsManifest']) : null,
            'SegmentControl' => isset($json['segmentControl']) ? (!HlsSegmentControl::exists((string) $json['segmentControl']) ? HlsSegmentControl::UNKNOWN_TO_SDK : (string) $json['segmentControl']) : null,
            'SegmentLength' => isset($json['segmentLength']) ? (int) $json['segmentLength'] : null,
            'SegmentLengthControl' => isset($json['segmentLengthControl']) ? (!HlsSegmentLengthControl::exists((string) $json['segmentLengthControl']) ? HlsSegmentLengthControl::UNKNOWN_TO_SDK : (string) $json['segmentLengthControl']) : null,
            'SegmentsPerSubdirectory' => isset($json['segmentsPerSubdirectory']) ? (int) $json['segmentsPerSubdirectory'] : null,
            'StreamInfResolution' => isset($json['streamInfResolution']) ? (!HlsStreamInfResolution::exists((string) $json['streamInfResolution']) ? HlsStreamInfResolution::UNKNOWN_TO_SDK : (string) $json['streamInfResolution']) : null,
            'TargetDurationCompatibilityMode' => isset($json['targetDurationCompatibilityMode']) ? (!HlsTargetDurationCompatibilityMode::exists((string) $json['targetDurationCompatibilityMode']) ? HlsTargetDurationCompatibilityMode::UNKNOWN_TO_SDK : (string) $json['targetDurationCompatibilityMode']) : null,
            'TimedMetadataId3Frame' => isset($json['timedMetadataId3Frame']) ? (!HlsTimedMetadataId3Frame::exists((string) $json['timedMetadataId3Frame']) ? HlsTimedMetadataId3Frame::UNKNOWN_TO_SDK : (string) $json['timedMetadataId3Frame']) : null,
            'TimedMetadataId3Period' => isset($json['timedMetadataId3Period']) ? (int) $json['timedMetadataId3Period'] : null,
            'TimestampDeltaMilliseconds' => isset($json['timestampDeltaMilliseconds']) ? (int) $json['timestampDeltaMilliseconds'] : null,
        ]);
    }

    private function populateResultHlsImageBasedTrickPlaySettings(array $json): HlsImageBasedTrickPlaySettings
    {
        return new HlsImageBasedTrickPlaySettings([
            'IntervalCadence' => isset($json['intervalCadence']) ? (!HlsIntervalCadence::exists((string) $json['intervalCadence']) ? HlsIntervalCadence::UNKNOWN_TO_SDK : (string) $json['intervalCadence']) : null,
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
            'RenditionLanguageCode' => isset($json['renditionLanguageCode']) ? (!LanguageCode::exists((string) $json['renditionLanguageCode']) ? LanguageCode::UNKNOWN_TO_SDK : (string) $json['renditionLanguageCode']) : null,
            'RenditionName' => isset($json['renditionName']) ? (string) $json['renditionName'] : null,
        ]);
    }

    private function populateResultHlsSettings(array $json): HlsSettings
    {
        return new HlsSettings([
            'AudioGroupId' => isset($json['audioGroupId']) ? (string) $json['audioGroupId'] : null,
            'AudioOnlyContainer' => isset($json['audioOnlyContainer']) ? (!HlsAudioOnlyContainer::exists((string) $json['audioOnlyContainer']) ? HlsAudioOnlyContainer::UNKNOWN_TO_SDK : (string) $json['audioOnlyContainer']) : null,
            'AudioRenditionSets' => isset($json['audioRenditionSets']) ? (string) $json['audioRenditionSets'] : null,
            'AudioTrackType' => isset($json['audioTrackType']) ? (!HlsAudioTrackType::exists((string) $json['audioTrackType']) ? HlsAudioTrackType::UNKNOWN_TO_SDK : (string) $json['audioTrackType']) : null,
            'DescriptiveVideoServiceFlag' => isset($json['descriptiveVideoServiceFlag']) ? (!HlsDescriptiveVideoServiceFlag::exists((string) $json['descriptiveVideoServiceFlag']) ? HlsDescriptiveVideoServiceFlag::UNKNOWN_TO_SDK : (string) $json['descriptiveVideoServiceFlag']) : null,
            'IFrameOnlyManifest' => isset($json['iFrameOnlyManifest']) ? (!HlsIFrameOnlyManifest::exists((string) $json['iFrameOnlyManifest']) ? HlsIFrameOnlyManifest::UNKNOWN_TO_SDK : (string) $json['iFrameOnlyManifest']) : null,
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
            'Accessibility' => isset($json['accessibility']) ? (!ImscAccessibilitySubs::exists((string) $json['accessibility']) ? ImscAccessibilitySubs::UNKNOWN_TO_SDK : (string) $json['accessibility']) : null,
            'StylePassthrough' => isset($json['stylePassthrough']) ? (!ImscStylePassthrough::exists((string) $json['stylePassthrough']) ? ImscStylePassthrough::UNKNOWN_TO_SDK : (string) $json['stylePassthrough']) : null,
        ]);
    }

    private function populateResultInput(array $json): Input
    {
        return new Input([
            'AdvancedInputFilter' => isset($json['advancedInputFilter']) ? (!AdvancedInputFilter::exists((string) $json['advancedInputFilter']) ? AdvancedInputFilter::UNKNOWN_TO_SDK : (string) $json['advancedInputFilter']) : null,
            'AdvancedInputFilterSettings' => empty($json['advancedInputFilterSettings']) ? null : $this->populateResultAdvancedInputFilterSettings($json['advancedInputFilterSettings']),
            'AudioSelectorGroups' => !isset($json['audioSelectorGroups']) ? null : $this->populateResult__mapOfAudioSelectorGroup($json['audioSelectorGroups']),
            'AudioSelectors' => !isset($json['audioSelectors']) ? null : $this->populateResult__mapOfAudioSelector($json['audioSelectors']),
            'CaptionSelectors' => !isset($json['captionSelectors']) ? null : $this->populateResult__mapOfCaptionSelector($json['captionSelectors']),
            'Crop' => empty($json['crop']) ? null : $this->populateResultRectangle($json['crop']),
            'DeblockFilter' => isset($json['deblockFilter']) ? (!InputDeblockFilter::exists((string) $json['deblockFilter']) ? InputDeblockFilter::UNKNOWN_TO_SDK : (string) $json['deblockFilter']) : null,
            'DecryptionSettings' => empty($json['decryptionSettings']) ? null : $this->populateResultInputDecryptionSettings($json['decryptionSettings']),
            'DenoiseFilter' => isset($json['denoiseFilter']) ? (!InputDenoiseFilter::exists((string) $json['denoiseFilter']) ? InputDenoiseFilter::UNKNOWN_TO_SDK : (string) $json['denoiseFilter']) : null,
            'DolbyVisionMetadataXml' => isset($json['dolbyVisionMetadataXml']) ? (string) $json['dolbyVisionMetadataXml'] : null,
            'DynamicAudioSelectors' => !isset($json['dynamicAudioSelectors']) ? null : $this->populateResult__mapOfDynamicAudioSelector($json['dynamicAudioSelectors']),
            'FileInput' => isset($json['fileInput']) ? (string) $json['fileInput'] : null,
            'FilterEnable' => isset($json['filterEnable']) ? (!InputFilterEnable::exists((string) $json['filterEnable']) ? InputFilterEnable::UNKNOWN_TO_SDK : (string) $json['filterEnable']) : null,
            'FilterStrength' => isset($json['filterStrength']) ? (int) $json['filterStrength'] : null,
            'ImageInserter' => empty($json['imageInserter']) ? null : $this->populateResultImageInserter($json['imageInserter']),
            'InputClippings' => !isset($json['inputClippings']) ? null : $this->populateResult__listOfInputClipping($json['inputClippings']),
            'InputScanType' => isset($json['inputScanType']) ? (!InputScanType::exists((string) $json['inputScanType']) ? InputScanType::UNKNOWN_TO_SDK : (string) $json['inputScanType']) : null,
            'Position' => empty($json['position']) ? null : $this->populateResultRectangle($json['position']),
            'ProgramNumber' => isset($json['programNumber']) ? (int) $json['programNumber'] : null,
            'PsiControl' => isset($json['psiControl']) ? (!InputPsiControl::exists((string) $json['psiControl']) ? InputPsiControl::UNKNOWN_TO_SDK : (string) $json['psiControl']) : null,
            'SupplementalImps' => !isset($json['supplementalImps']) ? null : $this->populateResult__listOf__stringPatternS3ASSETMAPXml($json['supplementalImps']),
            'TamsSettings' => empty($json['tamsSettings']) ? null : $this->populateResultInputTamsSettings($json['tamsSettings']),
            'TimecodeSource' => isset($json['timecodeSource']) ? (!InputTimecodeSource::exists((string) $json['timecodeSource']) ? InputTimecodeSource::UNKNOWN_TO_SDK : (string) $json['timecodeSource']) : null,
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
            'DecryptionMode' => isset($json['decryptionMode']) ? (!DecryptionMode::exists((string) $json['decryptionMode']) ? DecryptionMode::UNKNOWN_TO_SDK : (string) $json['decryptionMode']) : null,
            'EncryptedDecryptionKey' => isset($json['encryptedDecryptionKey']) ? (string) $json['encryptedDecryptionKey'] : null,
            'InitializationVector' => isset($json['initializationVector']) ? (string) $json['initializationVector'] : null,
            'KmsKeyRegion' => isset($json['kmsKeyRegion']) ? (string) $json['kmsKeyRegion'] : null,
        ]);
    }

    private function populateResultInputTamsSettings(array $json): InputTamsSettings
    {
        return new InputTamsSettings([
            'AuthConnectionArn' => isset($json['authConnectionArn']) ? (string) $json['authConnectionArn'] : null,
            'GapHandling' => isset($json['gapHandling']) ? (!TamsGapHandling::exists((string) $json['gapHandling']) ? TamsGapHandling::UNKNOWN_TO_SDK : (string) $json['gapHandling']) : null,
            'SourceId' => isset($json['sourceId']) ? (string) $json['sourceId'] : null,
            'Timerange' => isset($json['timerange']) ? (string) $json['timerange'] : null,
        ]);
    }

    private function populateResultInputVideoGenerator(array $json): InputVideoGenerator
    {
        return new InputVideoGenerator([
            'Channels' => isset($json['channels']) ? (int) $json['channels'] : null,
            'Duration' => isset($json['duration']) ? (int) $json['duration'] : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'ImageInput' => isset($json['imageInput']) ? (string) $json['imageInput'] : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
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
            'AccelerationStatus' => isset($json['accelerationStatus']) ? (!AccelerationStatus::exists((string) $json['accelerationStatus']) ? AccelerationStatus::UNKNOWN_TO_SDK : (string) $json['accelerationStatus']) : null,
            'Arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'BillingTagsSource' => isset($json['billingTagsSource']) ? (!BillingTagsSource::exists((string) $json['billingTagsSource']) ? BillingTagsSource::UNKNOWN_TO_SDK : (string) $json['billingTagsSource']) : null,
            'ClientRequestToken' => isset($json['clientRequestToken']) ? (string) $json['clientRequestToken'] : null,
            'CreatedAt' => isset($json['createdAt']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['createdAt']))) ? $d : null,
            'CurrentPhase' => isset($json['currentPhase']) ? (!JobPhase::exists((string) $json['currentPhase']) ? JobPhase::UNKNOWN_TO_SDK : (string) $json['currentPhase']) : null,
            'ErrorCode' => isset($json['errorCode']) ? (int) $json['errorCode'] : null,
            'ErrorMessage' => isset($json['errorMessage']) ? (string) $json['errorMessage'] : null,
            'HopDestinations' => !isset($json['hopDestinations']) ? null : $this->populateResult__listOfHopDestination($json['hopDestinations']),
            'Id' => isset($json['id']) ? (string) $json['id'] : null,
            'JobEngineVersionRequested' => isset($json['jobEngineVersionRequested']) ? (string) $json['jobEngineVersionRequested'] : null,
            'JobEngineVersionUsed' => isset($json['jobEngineVersionUsed']) ? (string) $json['jobEngineVersionUsed'] : null,
            'JobPercentComplete' => isset($json['jobPercentComplete']) ? (int) $json['jobPercentComplete'] : null,
            'JobTemplate' => isset($json['jobTemplate']) ? (string) $json['jobTemplate'] : null,
            'LastShareDetails' => isset($json['lastShareDetails']) ? (string) $json['lastShareDetails'] : null,
            'Messages' => empty($json['messages']) ? null : $this->populateResultJobMessages($json['messages']),
            'OutputGroupDetails' => !isset($json['outputGroupDetails']) ? null : $this->populateResult__listOfOutputGroupDetail($json['outputGroupDetails']),
            'Priority' => isset($json['priority']) ? (int) $json['priority'] : null,
            'Queue' => isset($json['queue']) ? (string) $json['queue'] : null,
            'QueueTransitions' => !isset($json['queueTransitions']) ? null : $this->populateResult__listOfQueueTransition($json['queueTransitions']),
            'RetryCount' => isset($json['retryCount']) ? (int) $json['retryCount'] : null,
            'Role' => (string) $json['role'],
            'Settings' => $this->populateResultJobSettings($json['settings']),
            'ShareStatus' => isset($json['shareStatus']) ? (!ShareStatus::exists((string) $json['shareStatus']) ? ShareStatus::UNKNOWN_TO_SDK : (string) $json['shareStatus']) : null,
            'SimulateReservedQueue' => isset($json['simulateReservedQueue']) ? (!SimulateReservedQueue::exists((string) $json['simulateReservedQueue']) ? SimulateReservedQueue::UNKNOWN_TO_SDK : (string) $json['simulateReservedQueue']) : null,
            'Status' => isset($json['status']) ? (!JobStatus::exists((string) $json['status']) ? JobStatus::UNKNOWN_TO_SDK : (string) $json['status']) : null,
            'StatusUpdateInterval' => isset($json['statusUpdateInterval']) ? (!StatusUpdateInterval::exists((string) $json['statusUpdateInterval']) ? StatusUpdateInterval::UNKNOWN_TO_SDK : (string) $json['statusUpdateInterval']) : null,
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
            'AudioBufferModel' => isset($json['audioBufferModel']) ? (!M2tsAudioBufferModel::exists((string) $json['audioBufferModel']) ? M2tsAudioBufferModel::UNKNOWN_TO_SDK : (string) $json['audioBufferModel']) : null,
            'AudioDuration' => isset($json['audioDuration']) ? (!M2tsAudioDuration::exists((string) $json['audioDuration']) ? M2tsAudioDuration::UNKNOWN_TO_SDK : (string) $json['audioDuration']) : null,
            'AudioFramesPerPes' => isset($json['audioFramesPerPes']) ? (int) $json['audioFramesPerPes'] : null,
            'AudioPids' => !isset($json['audioPids']) ? null : $this->populateResult__listOf__integerMin32Max8182($json['audioPids']),
            'AudioPtsOffsetDelta' => isset($json['audioPtsOffsetDelta']) ? (int) $json['audioPtsOffsetDelta'] : null,
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'BufferModel' => isset($json['bufferModel']) ? (!M2tsBufferModel::exists((string) $json['bufferModel']) ? M2tsBufferModel::UNKNOWN_TO_SDK : (string) $json['bufferModel']) : null,
            'DataPTSControl' => isset($json['dataPTSControl']) ? (!M2tsDataPtsControl::exists((string) $json['dataPTSControl']) ? M2tsDataPtsControl::UNKNOWN_TO_SDK : (string) $json['dataPTSControl']) : null,
            'DvbNitSettings' => empty($json['dvbNitSettings']) ? null : $this->populateResultDvbNitSettings($json['dvbNitSettings']),
            'DvbSdtSettings' => empty($json['dvbSdtSettings']) ? null : $this->populateResultDvbSdtSettings($json['dvbSdtSettings']),
            'DvbSubPids' => !isset($json['dvbSubPids']) ? null : $this->populateResult__listOf__integerMin32Max8182($json['dvbSubPids']),
            'DvbTdtSettings' => empty($json['dvbTdtSettings']) ? null : $this->populateResultDvbTdtSettings($json['dvbTdtSettings']),
            'DvbTeletextPid' => isset($json['dvbTeletextPid']) ? (int) $json['dvbTeletextPid'] : null,
            'EbpAudioInterval' => isset($json['ebpAudioInterval']) ? (!M2tsEbpAudioInterval::exists((string) $json['ebpAudioInterval']) ? M2tsEbpAudioInterval::UNKNOWN_TO_SDK : (string) $json['ebpAudioInterval']) : null,
            'EbpPlacement' => isset($json['ebpPlacement']) ? (!M2tsEbpPlacement::exists((string) $json['ebpPlacement']) ? M2tsEbpPlacement::UNKNOWN_TO_SDK : (string) $json['ebpPlacement']) : null,
            'EsRateInPes' => isset($json['esRateInPes']) ? (!M2tsEsRateInPes::exists((string) $json['esRateInPes']) ? M2tsEsRateInPes::UNKNOWN_TO_SDK : (string) $json['esRateInPes']) : null,
            'ForceTsVideoEbpOrder' => isset($json['forceTsVideoEbpOrder']) ? (!M2tsForceTsVideoEbpOrder::exists((string) $json['forceTsVideoEbpOrder']) ? M2tsForceTsVideoEbpOrder::UNKNOWN_TO_SDK : (string) $json['forceTsVideoEbpOrder']) : null,
            'FragmentTime' => isset($json['fragmentTime']) ? (float) $json['fragmentTime'] : null,
            'KlvMetadata' => isset($json['klvMetadata']) ? (!M2tsKlvMetadata::exists((string) $json['klvMetadata']) ? M2tsKlvMetadata::UNKNOWN_TO_SDK : (string) $json['klvMetadata']) : null,
            'MaxPcrInterval' => isset($json['maxPcrInterval']) ? (int) $json['maxPcrInterval'] : null,
            'MinEbpInterval' => isset($json['minEbpInterval']) ? (int) $json['minEbpInterval'] : null,
            'NielsenId3' => isset($json['nielsenId3']) ? (!M2tsNielsenId3::exists((string) $json['nielsenId3']) ? M2tsNielsenId3::UNKNOWN_TO_SDK : (string) $json['nielsenId3']) : null,
            'NullPacketBitrate' => isset($json['nullPacketBitrate']) ? (float) $json['nullPacketBitrate'] : null,
            'PatInterval' => isset($json['patInterval']) ? (int) $json['patInterval'] : null,
            'PcrControl' => isset($json['pcrControl']) ? (!M2tsPcrControl::exists((string) $json['pcrControl']) ? M2tsPcrControl::UNKNOWN_TO_SDK : (string) $json['pcrControl']) : null,
            'PcrPid' => isset($json['pcrPid']) ? (int) $json['pcrPid'] : null,
            'PmtInterval' => isset($json['pmtInterval']) ? (int) $json['pmtInterval'] : null,
            'PmtPid' => isset($json['pmtPid']) ? (int) $json['pmtPid'] : null,
            'PreventBufferUnderflow' => isset($json['preventBufferUnderflow']) ? (!M2tsPreventBufferUnderflow::exists((string) $json['preventBufferUnderflow']) ? M2tsPreventBufferUnderflow::UNKNOWN_TO_SDK : (string) $json['preventBufferUnderflow']) : null,
            'PrivateMetadataPid' => isset($json['privateMetadataPid']) ? (int) $json['privateMetadataPid'] : null,
            'ProgramNumber' => isset($json['programNumber']) ? (int) $json['programNumber'] : null,
            'PtsOffset' => isset($json['ptsOffset']) ? (int) $json['ptsOffset'] : null,
            'PtsOffsetMode' => isset($json['ptsOffsetMode']) ? (!TsPtsOffset::exists((string) $json['ptsOffsetMode']) ? TsPtsOffset::UNKNOWN_TO_SDK : (string) $json['ptsOffsetMode']) : null,
            'RateMode' => isset($json['rateMode']) ? (!M2tsRateMode::exists((string) $json['rateMode']) ? M2tsRateMode::UNKNOWN_TO_SDK : (string) $json['rateMode']) : null,
            'Scte35Esam' => empty($json['scte35Esam']) ? null : $this->populateResultM2tsScte35Esam($json['scte35Esam']),
            'Scte35Pid' => isset($json['scte35Pid']) ? (int) $json['scte35Pid'] : null,
            'Scte35Source' => isset($json['scte35Source']) ? (!M2tsScte35Source::exists((string) $json['scte35Source']) ? M2tsScte35Source::UNKNOWN_TO_SDK : (string) $json['scte35Source']) : null,
            'SegmentationMarkers' => isset($json['segmentationMarkers']) ? (!M2tsSegmentationMarkers::exists((string) $json['segmentationMarkers']) ? M2tsSegmentationMarkers::UNKNOWN_TO_SDK : (string) $json['segmentationMarkers']) : null,
            'SegmentationStyle' => isset($json['segmentationStyle']) ? (!M2tsSegmentationStyle::exists((string) $json['segmentationStyle']) ? M2tsSegmentationStyle::UNKNOWN_TO_SDK : (string) $json['segmentationStyle']) : null,
            'SegmentationTime' => isset($json['segmentationTime']) ? (float) $json['segmentationTime'] : null,
            'TimedMetadataPid' => isset($json['timedMetadataPid']) ? (int) $json['timedMetadataPid'] : null,
            'TransportStreamId' => isset($json['transportStreamId']) ? (int) $json['transportStreamId'] : null,
            'VideoPid' => isset($json['videoPid']) ? (int) $json['videoPid'] : null,
        ]);
    }

    private function populateResultM3u8Settings(array $json): M3u8Settings
    {
        return new M3u8Settings([
            'AudioDuration' => isset($json['audioDuration']) ? (!M3u8AudioDuration::exists((string) $json['audioDuration']) ? M3u8AudioDuration::UNKNOWN_TO_SDK : (string) $json['audioDuration']) : null,
            'AudioFramesPerPes' => isset($json['audioFramesPerPes']) ? (int) $json['audioFramesPerPes'] : null,
            'AudioPids' => !isset($json['audioPids']) ? null : $this->populateResult__listOf__integerMin32Max8182($json['audioPids']),
            'AudioPtsOffsetDelta' => isset($json['audioPtsOffsetDelta']) ? (int) $json['audioPtsOffsetDelta'] : null,
            'DataPTSControl' => isset($json['dataPTSControl']) ? (!M3u8DataPtsControl::exists((string) $json['dataPTSControl']) ? M3u8DataPtsControl::UNKNOWN_TO_SDK : (string) $json['dataPTSControl']) : null,
            'MaxPcrInterval' => isset($json['maxPcrInterval']) ? (int) $json['maxPcrInterval'] : null,
            'NielsenId3' => isset($json['nielsenId3']) ? (!M3u8NielsenId3::exists((string) $json['nielsenId3']) ? M3u8NielsenId3::UNKNOWN_TO_SDK : (string) $json['nielsenId3']) : null,
            'PatInterval' => isset($json['patInterval']) ? (int) $json['patInterval'] : null,
            'PcrControl' => isset($json['pcrControl']) ? (!M3u8PcrControl::exists((string) $json['pcrControl']) ? M3u8PcrControl::UNKNOWN_TO_SDK : (string) $json['pcrControl']) : null,
            'PcrPid' => isset($json['pcrPid']) ? (int) $json['pcrPid'] : null,
            'PmtInterval' => isset($json['pmtInterval']) ? (int) $json['pmtInterval'] : null,
            'PmtPid' => isset($json['pmtPid']) ? (int) $json['pmtPid'] : null,
            'PrivateMetadataPid' => isset($json['privateMetadataPid']) ? (int) $json['privateMetadataPid'] : null,
            'ProgramNumber' => isset($json['programNumber']) ? (int) $json['programNumber'] : null,
            'PtsOffset' => isset($json['ptsOffset']) ? (int) $json['ptsOffset'] : null,
            'PtsOffsetMode' => isset($json['ptsOffsetMode']) ? (!TsPtsOffset::exists((string) $json['ptsOffsetMode']) ? TsPtsOffset::UNKNOWN_TO_SDK : (string) $json['ptsOffsetMode']) : null,
            'Scte35Pid' => isset($json['scte35Pid']) ? (int) $json['scte35Pid'] : null,
            'Scte35Source' => isset($json['scte35Source']) ? (!M3u8Scte35Source::exists((string) $json['scte35Source']) ? M3u8Scte35Source::UNKNOWN_TO_SDK : (string) $json['scte35Source']) : null,
            'TimedMetadata' => isset($json['timedMetadata']) ? (!TimedMetadata::exists((string) $json['timedMetadata']) ? TimedMetadata::UNKNOWN_TO_SDK : (string) $json['timedMetadata']) : null,
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
            'InsertionMode' => isset($json['insertionMode']) ? (!MotionImageInsertionMode::exists((string) $json['insertionMode']) ? MotionImageInsertionMode::UNKNOWN_TO_SDK : (string) $json['insertionMode']) : null,
            'Offset' => empty($json['offset']) ? null : $this->populateResultMotionImageInsertionOffset($json['offset']),
            'Playback' => isset($json['playback']) ? (!MotionImagePlayback::exists((string) $json['playback']) ? MotionImagePlayback::UNKNOWN_TO_SDK : (string) $json['playback']) : null,
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
            'ClapAtom' => isset($json['clapAtom']) ? (!MovClapAtom::exists((string) $json['clapAtom']) ? MovClapAtom::UNKNOWN_TO_SDK : (string) $json['clapAtom']) : null,
            'CslgAtom' => isset($json['cslgAtom']) ? (!MovCslgAtom::exists((string) $json['cslgAtom']) ? MovCslgAtom::UNKNOWN_TO_SDK : (string) $json['cslgAtom']) : null,
            'Mpeg2FourCCControl' => isset($json['mpeg2FourCCControl']) ? (!MovMpeg2FourCCControl::exists((string) $json['mpeg2FourCCControl']) ? MovMpeg2FourCCControl::UNKNOWN_TO_SDK : (string) $json['mpeg2FourCCControl']) : null,
            'PaddingControl' => isset($json['paddingControl']) ? (!MovPaddingControl::exists((string) $json['paddingControl']) ? MovPaddingControl::UNKNOWN_TO_SDK : (string) $json['paddingControl']) : null,
            'Reference' => isset($json['reference']) ? (!MovReference::exists((string) $json['reference']) ? MovReference::UNKNOWN_TO_SDK : (string) $json['reference']) : null,
        ]);
    }

    private function populateResultMp2Settings(array $json): Mp2Settings
    {
        return new Mp2Settings([
            'AudioDescriptionMix' => isset($json['audioDescriptionMix']) ? (!Mp2AudioDescriptionMix::exists((string) $json['audioDescriptionMix']) ? Mp2AudioDescriptionMix::UNKNOWN_TO_SDK : (string) $json['audioDescriptionMix']) : null,
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
            'RateControlMode' => isset($json['rateControlMode']) ? (!Mp3RateControlMode::exists((string) $json['rateControlMode']) ? Mp3RateControlMode::UNKNOWN_TO_SDK : (string) $json['rateControlMode']) : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
            'VbrQuality' => isset($json['vbrQuality']) ? (int) $json['vbrQuality'] : null,
        ]);
    }

    private function populateResultMp4Settings(array $json): Mp4Settings
    {
        return new Mp4Settings([
            'AudioDuration' => isset($json['audioDuration']) ? (!CmfcAudioDuration::exists((string) $json['audioDuration']) ? CmfcAudioDuration::UNKNOWN_TO_SDK : (string) $json['audioDuration']) : null,
            'C2paManifest' => isset($json['c2paManifest']) ? (!Mp4C2paManifest::exists((string) $json['c2paManifest']) ? Mp4C2paManifest::UNKNOWN_TO_SDK : (string) $json['c2paManifest']) : null,
            'CertificateSecret' => isset($json['certificateSecret']) ? (string) $json['certificateSecret'] : null,
            'CslgAtom' => isset($json['cslgAtom']) ? (!Mp4CslgAtom::exists((string) $json['cslgAtom']) ? Mp4CslgAtom::UNKNOWN_TO_SDK : (string) $json['cslgAtom']) : null,
            'CttsVersion' => isset($json['cttsVersion']) ? (int) $json['cttsVersion'] : null,
            'FreeSpaceBox' => isset($json['freeSpaceBox']) ? (!Mp4FreeSpaceBox::exists((string) $json['freeSpaceBox']) ? Mp4FreeSpaceBox::UNKNOWN_TO_SDK : (string) $json['freeSpaceBox']) : null,
            'MoovPlacement' => isset($json['moovPlacement']) ? (!Mp4MoovPlacement::exists((string) $json['moovPlacement']) ? Mp4MoovPlacement::UNKNOWN_TO_SDK : (string) $json['moovPlacement']) : null,
            'Mp4MajorBrand' => isset($json['mp4MajorBrand']) ? (string) $json['mp4MajorBrand'] : null,
            'SigningKmsKey' => isset($json['signingKmsKey']) ? (string) $json['signingKmsKey'] : null,
        ]);
    }

    private function populateResultMpdSettings(array $json): MpdSettings
    {
        return new MpdSettings([
            'AccessibilityCaptionHints' => isset($json['accessibilityCaptionHints']) ? (!MpdAccessibilityCaptionHints::exists((string) $json['accessibilityCaptionHints']) ? MpdAccessibilityCaptionHints::UNKNOWN_TO_SDK : (string) $json['accessibilityCaptionHints']) : null,
            'AudioDuration' => isset($json['audioDuration']) ? (!MpdAudioDuration::exists((string) $json['audioDuration']) ? MpdAudioDuration::UNKNOWN_TO_SDK : (string) $json['audioDuration']) : null,
            'C2paManifest' => isset($json['c2paManifest']) ? (!MpdC2paManifest::exists((string) $json['c2paManifest']) ? MpdC2paManifest::UNKNOWN_TO_SDK : (string) $json['c2paManifest']) : null,
            'CaptionContainerType' => isset($json['captionContainerType']) ? (!MpdCaptionContainerType::exists((string) $json['captionContainerType']) ? MpdCaptionContainerType::UNKNOWN_TO_SDK : (string) $json['captionContainerType']) : null,
            'CertificateSecret' => isset($json['certificateSecret']) ? (string) $json['certificateSecret'] : null,
            'KlvMetadata' => isset($json['klvMetadata']) ? (!MpdKlvMetadata::exists((string) $json['klvMetadata']) ? MpdKlvMetadata::UNKNOWN_TO_SDK : (string) $json['klvMetadata']) : null,
            'ManifestMetadataSignaling' => isset($json['manifestMetadataSignaling']) ? (!MpdManifestMetadataSignaling::exists((string) $json['manifestMetadataSignaling']) ? MpdManifestMetadataSignaling::UNKNOWN_TO_SDK : (string) $json['manifestMetadataSignaling']) : null,
            'Scte35Esam' => isset($json['scte35Esam']) ? (!MpdScte35Esam::exists((string) $json['scte35Esam']) ? MpdScte35Esam::UNKNOWN_TO_SDK : (string) $json['scte35Esam']) : null,
            'Scte35Source' => isset($json['scte35Source']) ? (!MpdScte35Source::exists((string) $json['scte35Source']) ? MpdScte35Source::UNKNOWN_TO_SDK : (string) $json['scte35Source']) : null,
            'SigningKmsKey' => isset($json['signingKmsKey']) ? (string) $json['signingKmsKey'] : null,
            'TimedMetadata' => isset($json['timedMetadata']) ? (!MpdTimedMetadata::exists((string) $json['timedMetadata']) ? MpdTimedMetadata::UNKNOWN_TO_SDK : (string) $json['timedMetadata']) : null,
            'TimedMetadataBoxVersion' => isset($json['timedMetadataBoxVersion']) ? (!MpdTimedMetadataBoxVersion::exists((string) $json['timedMetadataBoxVersion']) ? MpdTimedMetadataBoxVersion::UNKNOWN_TO_SDK : (string) $json['timedMetadataBoxVersion']) : null,
            'TimedMetadataSchemeIdUri' => isset($json['timedMetadataSchemeIdUri']) ? (string) $json['timedMetadataSchemeIdUri'] : null,
            'TimedMetadataValue' => isset($json['timedMetadataValue']) ? (string) $json['timedMetadataValue'] : null,
        ]);
    }

    private function populateResultMpeg2Settings(array $json): Mpeg2Settings
    {
        return new Mpeg2Settings([
            'AdaptiveQuantization' => isset($json['adaptiveQuantization']) ? (!Mpeg2AdaptiveQuantization::exists((string) $json['adaptiveQuantization']) ? Mpeg2AdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['adaptiveQuantization']) : null,
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'CodecLevel' => isset($json['codecLevel']) ? (!Mpeg2CodecLevel::exists((string) $json['codecLevel']) ? Mpeg2CodecLevel::UNKNOWN_TO_SDK : (string) $json['codecLevel']) : null,
            'CodecProfile' => isset($json['codecProfile']) ? (!Mpeg2CodecProfile::exists((string) $json['codecProfile']) ? Mpeg2CodecProfile::UNKNOWN_TO_SDK : (string) $json['codecProfile']) : null,
            'DynamicSubGop' => isset($json['dynamicSubGop']) ? (!Mpeg2DynamicSubGop::exists((string) $json['dynamicSubGop']) ? Mpeg2DynamicSubGop::UNKNOWN_TO_SDK : (string) $json['dynamicSubGop']) : null,
            'FramerateControl' => isset($json['framerateControl']) ? (!Mpeg2FramerateControl::exists((string) $json['framerateControl']) ? Mpeg2FramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!Mpeg2FramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? Mpeg2FramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopClosedCadence' => isset($json['gopClosedCadence']) ? (int) $json['gopClosedCadence'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'GopSizeUnits' => isset($json['gopSizeUnits']) ? (!Mpeg2GopSizeUnits::exists((string) $json['gopSizeUnits']) ? Mpeg2GopSizeUnits::UNKNOWN_TO_SDK : (string) $json['gopSizeUnits']) : null,
            'HrdBufferFinalFillPercentage' => isset($json['hrdBufferFinalFillPercentage']) ? (int) $json['hrdBufferFinalFillPercentage'] : null,
            'HrdBufferInitialFillPercentage' => isset($json['hrdBufferInitialFillPercentage']) ? (int) $json['hrdBufferInitialFillPercentage'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (!Mpeg2InterlaceMode::exists((string) $json['interlaceMode']) ? Mpeg2InterlaceMode::UNKNOWN_TO_SDK : (string) $json['interlaceMode']) : null,
            'IntraDcPrecision' => isset($json['intraDcPrecision']) ? (!Mpeg2IntraDcPrecision::exists((string) $json['intraDcPrecision']) ? Mpeg2IntraDcPrecision::UNKNOWN_TO_SDK : (string) $json['intraDcPrecision']) : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'MinIInterval' => isset($json['minIInterval']) ? (int) $json['minIInterval'] : null,
            'NumberBFramesBetweenReferenceFrames' => isset($json['numberBFramesBetweenReferenceFrames']) ? (int) $json['numberBFramesBetweenReferenceFrames'] : null,
            'ParControl' => isset($json['parControl']) ? (!Mpeg2ParControl::exists((string) $json['parControl']) ? Mpeg2ParControl::UNKNOWN_TO_SDK : (string) $json['parControl']) : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (!Mpeg2QualityTuningLevel::exists((string) $json['qualityTuningLevel']) ? Mpeg2QualityTuningLevel::UNKNOWN_TO_SDK : (string) $json['qualityTuningLevel']) : null,
            'RateControlMode' => isset($json['rateControlMode']) ? (!Mpeg2RateControlMode::exists((string) $json['rateControlMode']) ? Mpeg2RateControlMode::UNKNOWN_TO_SDK : (string) $json['rateControlMode']) : null,
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (!Mpeg2ScanTypeConversionMode::exists((string) $json['scanTypeConversionMode']) ? Mpeg2ScanTypeConversionMode::UNKNOWN_TO_SDK : (string) $json['scanTypeConversionMode']) : null,
            'SceneChangeDetect' => isset($json['sceneChangeDetect']) ? (!Mpeg2SceneChangeDetect::exists((string) $json['sceneChangeDetect']) ? Mpeg2SceneChangeDetect::UNKNOWN_TO_SDK : (string) $json['sceneChangeDetect']) : null,
            'SlowPal' => isset($json['slowPal']) ? (!Mpeg2SlowPal::exists((string) $json['slowPal']) ? Mpeg2SlowPal::UNKNOWN_TO_SDK : (string) $json['slowPal']) : null,
            'Softness' => isset($json['softness']) ? (int) $json['softness'] : null,
            'SpatialAdaptiveQuantization' => isset($json['spatialAdaptiveQuantization']) ? (!Mpeg2SpatialAdaptiveQuantization::exists((string) $json['spatialAdaptiveQuantization']) ? Mpeg2SpatialAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['spatialAdaptiveQuantization']) : null,
            'Syntax' => isset($json['syntax']) ? (!Mpeg2Syntax::exists((string) $json['syntax']) ? Mpeg2Syntax::UNKNOWN_TO_SDK : (string) $json['syntax']) : null,
            'Telecine' => isset($json['telecine']) ? (!Mpeg2Telecine::exists((string) $json['telecine']) ? Mpeg2Telecine::UNKNOWN_TO_SDK : (string) $json['telecine']) : null,
            'TemporalAdaptiveQuantization' => isset($json['temporalAdaptiveQuantization']) ? (!Mpeg2TemporalAdaptiveQuantization::exists((string) $json['temporalAdaptiveQuantization']) ? Mpeg2TemporalAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['temporalAdaptiveQuantization']) : null,
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
            'AudioDeduplication' => isset($json['audioDeduplication']) ? (!MsSmoothAudioDeduplication::exists((string) $json['audioDeduplication']) ? MsSmoothAudioDeduplication::UNKNOWN_TO_SDK : (string) $json['audioDeduplication']) : null,
            'Destination' => isset($json['destination']) ? (string) $json['destination'] : null,
            'DestinationSettings' => empty($json['destinationSettings']) ? null : $this->populateResultDestinationSettings($json['destinationSettings']),
            'Encryption' => empty($json['encryption']) ? null : $this->populateResultMsSmoothEncryptionSettings($json['encryption']),
            'FragmentLength' => isset($json['fragmentLength']) ? (int) $json['fragmentLength'] : null,
            'FragmentLengthControl' => isset($json['fragmentLengthControl']) ? (!MsSmoothFragmentLengthControl::exists((string) $json['fragmentLengthControl']) ? MsSmoothFragmentLengthControl::UNKNOWN_TO_SDK : (string) $json['fragmentLengthControl']) : null,
            'ManifestEncoding' => isset($json['manifestEncoding']) ? (!MsSmoothManifestEncoding::exists((string) $json['manifestEncoding']) ? MsSmoothManifestEncoding::UNKNOWN_TO_SDK : (string) $json['manifestEncoding']) : null,
        ]);
    }

    private function populateResultMxfSettings(array $json): MxfSettings
    {
        return new MxfSettings([
            'AfdSignaling' => isset($json['afdSignaling']) ? (!MxfAfdSignaling::exists((string) $json['afdSignaling']) ? MxfAfdSignaling::UNKNOWN_TO_SDK : (string) $json['afdSignaling']) : null,
            'Profile' => isset($json['profile']) ? (!MxfProfile::exists((string) $json['profile']) ? MxfProfile::UNKNOWN_TO_SDK : (string) $json['profile']) : null,
            'XavcProfileSettings' => empty($json['xavcProfileSettings']) ? null : $this->populateResultMxfXavcProfileSettings($json['xavcProfileSettings']),
        ]);
    }

    private function populateResultMxfXavcProfileSettings(array $json): MxfXavcProfileSettings
    {
        return new MxfXavcProfileSettings([
            'DurationMode' => isset($json['durationMode']) ? (!MxfXavcDurationMode::exists((string) $json['durationMode']) ? MxfXavcDurationMode::UNKNOWN_TO_SDK : (string) $json['durationMode']) : null,
            'MaxAncDataSize' => isset($json['maxAncDataSize']) ? (int) $json['maxAncDataSize'] : null,
        ]);
    }

    private function populateResultNexGuardFileMarkerSettings(array $json): NexGuardFileMarkerSettings
    {
        return new NexGuardFileMarkerSettings([
            'License' => isset($json['license']) ? (string) $json['license'] : null,
            'Payload' => isset($json['payload']) ? (int) $json['payload'] : null,
            'Preset' => isset($json['preset']) ? (string) $json['preset'] : null,
            'Strength' => isset($json['strength']) ? (!WatermarkingStrength::exists((string) $json['strength']) ? WatermarkingStrength::UNKNOWN_TO_SDK : (string) $json['strength']) : null,
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
            'ActiveWatermarkProcess' => isset($json['activeWatermarkProcess']) ? (!NielsenActiveWatermarkProcessType::exists((string) $json['activeWatermarkProcess']) ? NielsenActiveWatermarkProcessType::UNKNOWN_TO_SDK : (string) $json['activeWatermarkProcess']) : null,
            'AdiFilename' => isset($json['adiFilename']) ? (string) $json['adiFilename'] : null,
            'AssetId' => isset($json['assetId']) ? (string) $json['assetId'] : null,
            'AssetName' => isset($json['assetName']) ? (string) $json['assetName'] : null,
            'CbetSourceId' => isset($json['cbetSourceId']) ? (string) $json['cbetSourceId'] : null,
            'EpisodeId' => isset($json['episodeId']) ? (string) $json['episodeId'] : null,
            'MetadataDestination' => isset($json['metadataDestination']) ? (string) $json['metadataDestination'] : null,
            'SourceId' => isset($json['sourceId']) ? (int) $json['sourceId'] : null,
            'SourceWatermarkStatus' => isset($json['sourceWatermarkStatus']) ? (!NielsenSourceWatermarkStatusType::exists((string) $json['sourceWatermarkStatus']) ? NielsenSourceWatermarkStatusType::UNKNOWN_TO_SDK : (string) $json['sourceWatermarkStatus']) : null,
            'TicServerUrl' => isset($json['ticServerUrl']) ? (string) $json['ticServerUrl'] : null,
            'UniqueTicPerAudioTrack' => isset($json['uniqueTicPerAudioTrack']) ? (!NielsenUniqueTicPerAudioTrackType::exists((string) $json['uniqueTicPerAudioTrack']) ? NielsenUniqueTicPerAudioTrackType::UNKNOWN_TO_SDK : (string) $json['uniqueTicPerAudioTrack']) : null,
        ]);
    }

    private function populateResultNoiseReducer(array $json): NoiseReducer
    {
        return new NoiseReducer([
            'Filter' => isset($json['filter']) ? (!NoiseReducerFilter::exists((string) $json['filter']) ? NoiseReducerFilter::UNKNOWN_TO_SDK : (string) $json['filter']) : null,
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
            'PostTemporalSharpening' => isset($json['postTemporalSharpening']) ? (!NoiseFilterPostTemporalSharpening::exists((string) $json['postTemporalSharpening']) ? NoiseFilterPostTemporalSharpening::UNKNOWN_TO_SDK : (string) $json['postTemporalSharpening']) : null,
            'PostTemporalSharpeningStrength' => isset($json['postTemporalSharpeningStrength']) ? (!NoiseFilterPostTemporalSharpeningStrength::exists((string) $json['postTemporalSharpeningStrength']) ? NoiseFilterPostTemporalSharpeningStrength::UNKNOWN_TO_SDK : (string) $json['postTemporalSharpeningStrength']) : null,
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
            'Type' => isset($json['type']) ? (!OutputGroupType::exists((string) $json['type']) ? OutputGroupType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
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

    private function populateResultPassthroughSettings(array $json): PassthroughSettings
    {
        return new PassthroughSettings([
            'FrameControl' => isset($json['frameControl']) ? (!FrameControl::exists((string) $json['frameControl']) ? FrameControl::UNKNOWN_TO_SDK : (string) $json['frameControl']) : null,
            'VideoSelectorMode' => isset($json['videoSelectorMode']) ? (!VideoSelectorMode::exists((string) $json['videoSelectorMode']) ? VideoSelectorMode::UNKNOWN_TO_SDK : (string) $json['videoSelectorMode']) : null,
        ]);
    }

    private function populateResultProresSettings(array $json): ProresSettings
    {
        return new ProresSettings([
            'ChromaSampling' => isset($json['chromaSampling']) ? (!ProresChromaSampling::exists((string) $json['chromaSampling']) ? ProresChromaSampling::UNKNOWN_TO_SDK : (string) $json['chromaSampling']) : null,
            'CodecProfile' => isset($json['codecProfile']) ? (!ProresCodecProfile::exists((string) $json['codecProfile']) ? ProresCodecProfile::UNKNOWN_TO_SDK : (string) $json['codecProfile']) : null,
            'FramerateControl' => isset($json['framerateControl']) ? (!ProresFramerateControl::exists((string) $json['framerateControl']) ? ProresFramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!ProresFramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? ProresFramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (!ProresInterlaceMode::exists((string) $json['interlaceMode']) ? ProresInterlaceMode::UNKNOWN_TO_SDK : (string) $json['interlaceMode']) : null,
            'ParControl' => isset($json['parControl']) ? (!ProresParControl::exists((string) $json['parControl']) ? ProresParControl::UNKNOWN_TO_SDK : (string) $json['parControl']) : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (!ProresScanTypeConversionMode::exists((string) $json['scanTypeConversionMode']) ? ProresScanTypeConversionMode::UNKNOWN_TO_SDK : (string) $json['scanTypeConversionMode']) : null,
            'SlowPal' => isset($json['slowPal']) ? (!ProresSlowPal::exists((string) $json['slowPal']) ? ProresSlowPal::UNKNOWN_TO_SDK : (string) $json['slowPal']) : null,
            'Telecine' => isset($json['telecine']) ? (!ProresTelecine::exists((string) $json['telecine']) ? ProresTelecine::UNKNOWN_TO_SDK : (string) $json['telecine']) : null,
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
            'CannedAcl' => isset($json['cannedAcl']) ? (!S3ObjectCannedAcl::exists((string) $json['cannedAcl']) ? S3ObjectCannedAcl::UNKNOWN_TO_SDK : (string) $json['cannedAcl']) : null,
        ]);
    }

    private function populateResultS3DestinationSettings(array $json): S3DestinationSettings
    {
        return new S3DestinationSettings([
            'AccessControl' => empty($json['accessControl']) ? null : $this->populateResultS3DestinationAccessControl($json['accessControl']),
            'Encryption' => empty($json['encryption']) ? null : $this->populateResultS3EncryptionSettings($json['encryption']),
            'StorageClass' => isset($json['storageClass']) ? (!S3StorageClass::exists((string) $json['storageClass']) ? S3StorageClass::UNKNOWN_TO_SDK : (string) $json['storageClass']) : null,
        ]);
    }

    private function populateResultS3EncryptionSettings(array $json): S3EncryptionSettings
    {
        return new S3EncryptionSettings([
            'EncryptionType' => isset($json['encryptionType']) ? (!S3ServerSideEncryptionType::exists((string) $json['encryptionType']) ? S3ServerSideEncryptionType::UNKNOWN_TO_SDK : (string) $json['encryptionType']) : null,
            'KmsEncryptionContext' => isset($json['kmsEncryptionContext']) ? (string) $json['kmsEncryptionContext'] : null,
            'KmsKeyArn' => isset($json['kmsKeyArn']) ? (string) $json['kmsKeyArn'] : null,
        ]);
    }

    private function populateResultSccDestinationSettings(array $json): SccDestinationSettings
    {
        return new SccDestinationSettings([
            'Framerate' => isset($json['framerate']) ? (!SccDestinationFramerate::exists((string) $json['framerate']) ? SccDestinationFramerate::UNKNOWN_TO_SDK : (string) $json['framerate']) : null,
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
            'StylePassthrough' => isset($json['stylePassthrough']) ? (!SrtStylePassthrough::exists((string) $json['stylePassthrough']) ? SrtStylePassthrough::UNKNOWN_TO_SDK : (string) $json['stylePassthrough']) : null,
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
            'Position' => isset($json['position']) ? (!TimecodeBurninPosition::exists((string) $json['position']) ? TimecodeBurninPosition::UNKNOWN_TO_SDK : (string) $json['position']) : null,
            'Prefix' => isset($json['prefix']) ? (string) $json['prefix'] : null,
        ]);
    }

    private function populateResultTimecodeConfig(array $json): TimecodeConfig
    {
        return new TimecodeConfig([
            'Anchor' => isset($json['anchor']) ? (string) $json['anchor'] : null,
            'Source' => isset($json['source']) ? (!TimecodeSource::exists((string) $json['source']) ? TimecodeSource::UNKNOWN_TO_SDK : (string) $json['source']) : null,
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
            'StreamNumber' => isset($json['streamNumber']) ? (int) $json['streamNumber'] : null,
            'TrackNumber' => isset($json['trackNumber']) ? (int) $json['trackNumber'] : null,
        ]);
    }

    private function populateResultTtmlDestinationSettings(array $json): TtmlDestinationSettings
    {
        return new TtmlDestinationSettings([
            'StylePassthrough' => isset($json['stylePassthrough']) ? (!TtmlStylePassthrough::exists((string) $json['stylePassthrough']) ? TtmlStylePassthrough::UNKNOWN_TO_SDK : (string) $json['stylePassthrough']) : null,
        ]);
    }

    private function populateResultUncompressedSettings(array $json): UncompressedSettings
    {
        return new UncompressedSettings([
            'Fourcc' => isset($json['fourcc']) ? (!UncompressedFourcc::exists((string) $json['fourcc']) ? UncompressedFourcc::UNKNOWN_TO_SDK : (string) $json['fourcc']) : null,
            'FramerateControl' => isset($json['framerateControl']) ? (!UncompressedFramerateControl::exists((string) $json['framerateControl']) ? UncompressedFramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!UncompressedFramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? UncompressedFramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (!UncompressedInterlaceMode::exists((string) $json['interlaceMode']) ? UncompressedInterlaceMode::UNKNOWN_TO_SDK : (string) $json['interlaceMode']) : null,
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (!UncompressedScanTypeConversionMode::exists((string) $json['scanTypeConversionMode']) ? UncompressedScanTypeConversionMode::UNKNOWN_TO_SDK : (string) $json['scanTypeConversionMode']) : null,
            'SlowPal' => isset($json['slowPal']) ? (!UncompressedSlowPal::exists((string) $json['slowPal']) ? UncompressedSlowPal::UNKNOWN_TO_SDK : (string) $json['slowPal']) : null,
            'Telecine' => isset($json['telecine']) ? (!UncompressedTelecine::exists((string) $json['telecine']) ? UncompressedTelecine::UNKNOWN_TO_SDK : (string) $json['telecine']) : null,
        ]);
    }

    private function populateResultVc3Settings(array $json): Vc3Settings
    {
        return new Vc3Settings([
            'FramerateControl' => isset($json['framerateControl']) ? (!Vc3FramerateControl::exists((string) $json['framerateControl']) ? Vc3FramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!Vc3FramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? Vc3FramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (!Vc3InterlaceMode::exists((string) $json['interlaceMode']) ? Vc3InterlaceMode::UNKNOWN_TO_SDK : (string) $json['interlaceMode']) : null,
            'ScanTypeConversionMode' => isset($json['scanTypeConversionMode']) ? (!Vc3ScanTypeConversionMode::exists((string) $json['scanTypeConversionMode']) ? Vc3ScanTypeConversionMode::UNKNOWN_TO_SDK : (string) $json['scanTypeConversionMode']) : null,
            'SlowPal' => isset($json['slowPal']) ? (!Vc3SlowPal::exists((string) $json['slowPal']) ? Vc3SlowPal::UNKNOWN_TO_SDK : (string) $json['slowPal']) : null,
            'Telecine' => isset($json['telecine']) ? (!Vc3Telecine::exists((string) $json['telecine']) ? Vc3Telecine::UNKNOWN_TO_SDK : (string) $json['telecine']) : null,
            'Vc3Class' => isset($json['vc3Class']) ? (!Vc3Class::exists((string) $json['vc3Class']) ? Vc3Class::UNKNOWN_TO_SDK : (string) $json['vc3Class']) : null,
        ]);
    }

    private function populateResultVideoCodecSettings(array $json): VideoCodecSettings
    {
        return new VideoCodecSettings([
            'Av1Settings' => empty($json['av1Settings']) ? null : $this->populateResultAv1Settings($json['av1Settings']),
            'AvcIntraSettings' => empty($json['avcIntraSettings']) ? null : $this->populateResultAvcIntraSettings($json['avcIntraSettings']),
            'Codec' => isset($json['codec']) ? (!VideoCodec::exists((string) $json['codec']) ? VideoCodec::UNKNOWN_TO_SDK : (string) $json['codec']) : null,
            'FrameCaptureSettings' => empty($json['frameCaptureSettings']) ? null : $this->populateResultFrameCaptureSettings($json['frameCaptureSettings']),
            'GifSettings' => empty($json['gifSettings']) ? null : $this->populateResultGifSettings($json['gifSettings']),
            'H264Settings' => empty($json['h264Settings']) ? null : $this->populateResultH264Settings($json['h264Settings']),
            'H265Settings' => empty($json['h265Settings']) ? null : $this->populateResultH265Settings($json['h265Settings']),
            'Mpeg2Settings' => empty($json['mpeg2Settings']) ? null : $this->populateResultMpeg2Settings($json['mpeg2Settings']),
            'PassthroughSettings' => empty($json['passthroughSettings']) ? null : $this->populateResultPassthroughSettings($json['passthroughSettings']),
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
            'AfdSignaling' => isset($json['afdSignaling']) ? (!AfdSignaling::exists((string) $json['afdSignaling']) ? AfdSignaling::UNKNOWN_TO_SDK : (string) $json['afdSignaling']) : null,
            'AntiAlias' => isset($json['antiAlias']) ? (!AntiAlias::exists((string) $json['antiAlias']) ? AntiAlias::UNKNOWN_TO_SDK : (string) $json['antiAlias']) : null,
            'ChromaPositionMode' => isset($json['chromaPositionMode']) ? (!ChromaPositionMode::exists((string) $json['chromaPositionMode']) ? ChromaPositionMode::UNKNOWN_TO_SDK : (string) $json['chromaPositionMode']) : null,
            'CodecSettings' => empty($json['codecSettings']) ? null : $this->populateResultVideoCodecSettings($json['codecSettings']),
            'ColorMetadata' => isset($json['colorMetadata']) ? (!ColorMetadata::exists((string) $json['colorMetadata']) ? ColorMetadata::UNKNOWN_TO_SDK : (string) $json['colorMetadata']) : null,
            'Crop' => empty($json['crop']) ? null : $this->populateResultRectangle($json['crop']),
            'DropFrameTimecode' => isset($json['dropFrameTimecode']) ? (!DropFrameTimecode::exists((string) $json['dropFrameTimecode']) ? DropFrameTimecode::UNKNOWN_TO_SDK : (string) $json['dropFrameTimecode']) : null,
            'FixedAfd' => isset($json['fixedAfd']) ? (int) $json['fixedAfd'] : null,
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'Position' => empty($json['position']) ? null : $this->populateResultRectangle($json['position']),
            'RespondToAfd' => isset($json['respondToAfd']) ? (!RespondToAfd::exists((string) $json['respondToAfd']) ? RespondToAfd::UNKNOWN_TO_SDK : (string) $json['respondToAfd']) : null,
            'ScalingBehavior' => isset($json['scalingBehavior']) ? (!ScalingBehavior::exists((string) $json['scalingBehavior']) ? ScalingBehavior::UNKNOWN_TO_SDK : (string) $json['scalingBehavior']) : null,
            'Sharpness' => isset($json['sharpness']) ? (int) $json['sharpness'] : null,
            'TimecodeInsertion' => isset($json['timecodeInsertion']) ? (!VideoTimecodeInsertion::exists((string) $json['timecodeInsertion']) ? VideoTimecodeInsertion::UNKNOWN_TO_SDK : (string) $json['timecodeInsertion']) : null,
            'TimecodeTrack' => isset($json['timecodeTrack']) ? (!TimecodeTrack::exists((string) $json['timecodeTrack']) ? TimecodeTrack::UNKNOWN_TO_SDK : (string) $json['timecodeTrack']) : null,
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
            'Playback' => isset($json['playback']) ? (!VideoOverlayPlayBackMode::exists((string) $json['playback']) ? VideoOverlayPlayBackMode::UNKNOWN_TO_SDK : (string) $json['playback']) : null,
            'StartTimecode' => isset($json['startTimecode']) ? (string) $json['startTimecode'] : null,
            'Transitions' => !isset($json['transitions']) ? null : $this->populateResult__listOfVideoOverlayTransition($json['transitions']),
        ]);
    }

    private function populateResultVideoOverlayCrop(array $json): VideoOverlayCrop
    {
        return new VideoOverlayCrop([
            'Height' => isset($json['height']) ? (int) $json['height'] : null,
            'Unit' => isset($json['unit']) ? (!VideoOverlayUnit::exists((string) $json['unit']) ? VideoOverlayUnit::UNKNOWN_TO_SDK : (string) $json['unit']) : null,
            'Width' => isset($json['width']) ? (int) $json['width'] : null,
            'X' => isset($json['x']) ? (int) $json['x'] : null,
            'Y' => isset($json['y']) ? (int) $json['y'] : null,
        ]);
    }

    private function populateResultVideoOverlayInput(array $json): VideoOverlayInput
    {
        return new VideoOverlayInput([
            'AudioSelectors' => !isset($json['audioSelectors']) ? null : $this->populateResult__mapOfAudioSelector($json['audioSelectors']),
            'FileInput' => isset($json['fileInput']) ? (string) $json['fileInput'] : null,
            'InputClippings' => !isset($json['inputClippings']) ? null : $this->populateResult__listOfVideoOverlayInputClipping($json['inputClippings']),
            'TimecodeSource' => isset($json['timecodeSource']) ? (!InputTimecodeSource::exists((string) $json['timecodeSource']) ? InputTimecodeSource::UNKNOWN_TO_SDK : (string) $json['timecodeSource']) : null,
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
            'Opacity' => isset($json['opacity']) ? (int) $json['opacity'] : null,
            'Unit' => isset($json['unit']) ? (!VideoOverlayUnit::exists((string) $json['unit']) ? VideoOverlayUnit::UNKNOWN_TO_SDK : (string) $json['unit']) : null,
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
            'AlphaBehavior' => isset($json['alphaBehavior']) ? (!AlphaBehavior::exists((string) $json['alphaBehavior']) ? AlphaBehavior::UNKNOWN_TO_SDK : (string) $json['alphaBehavior']) : null,
            'ColorSpace' => isset($json['colorSpace']) ? (!ColorSpace::exists((string) $json['colorSpace']) ? ColorSpace::UNKNOWN_TO_SDK : (string) $json['colorSpace']) : null,
            'ColorSpaceUsage' => isset($json['colorSpaceUsage']) ? (!ColorSpaceUsage::exists((string) $json['colorSpaceUsage']) ? ColorSpaceUsage::UNKNOWN_TO_SDK : (string) $json['colorSpaceUsage']) : null,
            'EmbeddedTimecodeOverride' => isset($json['embeddedTimecodeOverride']) ? (!EmbeddedTimecodeOverride::exists((string) $json['embeddedTimecodeOverride']) ? EmbeddedTimecodeOverride::UNKNOWN_TO_SDK : (string) $json['embeddedTimecodeOverride']) : null,
            'Hdr10Metadata' => empty($json['hdr10Metadata']) ? null : $this->populateResultHdr10Metadata($json['hdr10Metadata']),
            'MaxLuminance' => isset($json['maxLuminance']) ? (int) $json['maxLuminance'] : null,
            'PadVideo' => isset($json['padVideo']) ? (!PadVideo::exists((string) $json['padVideo']) ? PadVideo::UNKNOWN_TO_SDK : (string) $json['padVideo']) : null,
            'Pid' => isset($json['pid']) ? (int) $json['pid'] : null,
            'ProgramNumber' => isset($json['programNumber']) ? (int) $json['programNumber'] : null,
            'Rotate' => isset($json['rotate']) ? (!InputRotate::exists((string) $json['rotate']) ? InputRotate::UNKNOWN_TO_SDK : (string) $json['rotate']) : null,
            'SampleRange' => isset($json['sampleRange']) ? (!InputSampleRange::exists((string) $json['sampleRange']) ? InputSampleRange::UNKNOWN_TO_SDK : (string) $json['sampleRange']) : null,
            'SelectorType' => isset($json['selectorType']) ? (!VideoSelectorType::exists((string) $json['selectorType']) ? VideoSelectorType::UNKNOWN_TO_SDK : (string) $json['selectorType']) : null,
            'Streams' => !isset($json['streams']) ? null : $this->populateResult__listOf__integerMin1Max2147483647($json['streams']),
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
            'FramerateControl' => isset($json['framerateControl']) ? (!Vp8FramerateControl::exists((string) $json['framerateControl']) ? Vp8FramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!Vp8FramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? Vp8FramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'ParControl' => isset($json['parControl']) ? (!Vp8ParControl::exists((string) $json['parControl']) ? Vp8ParControl::UNKNOWN_TO_SDK : (string) $json['parControl']) : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (!Vp8QualityTuningLevel::exists((string) $json['qualityTuningLevel']) ? Vp8QualityTuningLevel::UNKNOWN_TO_SDK : (string) $json['qualityTuningLevel']) : null,
            'RateControlMode' => isset($json['rateControlMode']) ? (!Vp8RateControlMode::exists((string) $json['rateControlMode']) ? Vp8RateControlMode::UNKNOWN_TO_SDK : (string) $json['rateControlMode']) : null,
        ]);
    }

    private function populateResultVp9Settings(array $json): Vp9Settings
    {
        return new Vp9Settings([
            'Bitrate' => isset($json['bitrate']) ? (int) $json['bitrate'] : null,
            'FramerateControl' => isset($json['framerateControl']) ? (!Vp9FramerateControl::exists((string) $json['framerateControl']) ? Vp9FramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!Vp9FramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? Vp9FramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'GopSize' => isset($json['gopSize']) ? (float) $json['gopSize'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'MaxBitrate' => isset($json['maxBitrate']) ? (int) $json['maxBitrate'] : null,
            'ParControl' => isset($json['parControl']) ? (!Vp9ParControl::exists((string) $json['parControl']) ? Vp9ParControl::UNKNOWN_TO_SDK : (string) $json['parControl']) : null,
            'ParDenominator' => isset($json['parDenominator']) ? (int) $json['parDenominator'] : null,
            'ParNumerator' => isset($json['parNumerator']) ? (int) $json['parNumerator'] : null,
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (!Vp9QualityTuningLevel::exists((string) $json['qualityTuningLevel']) ? Vp9QualityTuningLevel::UNKNOWN_TO_SDK : (string) $json['qualityTuningLevel']) : null,
            'RateControlMode' => isset($json['rateControlMode']) ? (!Vp9RateControlMode::exists((string) $json['rateControlMode']) ? Vp9RateControlMode::UNKNOWN_TO_SDK : (string) $json['rateControlMode']) : null,
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
            'Format' => isset($json['format']) ? (!WavFormat::exists((string) $json['format']) ? WavFormat::UNKNOWN_TO_SDK : (string) $json['format']) : null,
            'SampleRate' => isset($json['sampleRate']) ? (int) $json['sampleRate'] : null,
        ]);
    }

    private function populateResultWebvttDestinationSettings(array $json): WebvttDestinationSettings
    {
        return new WebvttDestinationSettings([
            'Accessibility' => isset($json['accessibility']) ? (!WebvttAccessibilitySubs::exists((string) $json['accessibility']) ? WebvttAccessibilitySubs::UNKNOWN_TO_SDK : (string) $json['accessibility']) : null,
            'StylePassthrough' => isset($json['stylePassthrough']) ? (!WebvttStylePassthrough::exists((string) $json['stylePassthrough']) ? WebvttStylePassthrough::UNKNOWN_TO_SDK : (string) $json['stylePassthrough']) : null,
        ]);
    }

    private function populateResultWebvttHlsSourceSettings(array $json): WebvttHlsSourceSettings
    {
        return new WebvttHlsSourceSettings([
            'RenditionGroupId' => isset($json['renditionGroupId']) ? (string) $json['renditionGroupId'] : null,
            'RenditionLanguageCode' => isset($json['renditionLanguageCode']) ? (!LanguageCode::exists((string) $json['renditionLanguageCode']) ? LanguageCode::UNKNOWN_TO_SDK : (string) $json['renditionLanguageCode']) : null,
            'RenditionName' => isset($json['renditionName']) ? (string) $json['renditionName'] : null,
        ]);
    }

    private function populateResultXavc4kIntraCbgProfileSettings(array $json): Xavc4kIntraCbgProfileSettings
    {
        return new Xavc4kIntraCbgProfileSettings([
            'XavcClass' => isset($json['xavcClass']) ? (!Xavc4kIntraCbgProfileClass::exists((string) $json['xavcClass']) ? Xavc4kIntraCbgProfileClass::UNKNOWN_TO_SDK : (string) $json['xavcClass']) : null,
        ]);
    }

    private function populateResultXavc4kIntraVbrProfileSettings(array $json): Xavc4kIntraVbrProfileSettings
    {
        return new Xavc4kIntraVbrProfileSettings([
            'XavcClass' => isset($json['xavcClass']) ? (!Xavc4kIntraVbrProfileClass::exists((string) $json['xavcClass']) ? Xavc4kIntraVbrProfileClass::UNKNOWN_TO_SDK : (string) $json['xavcClass']) : null,
        ]);
    }

    private function populateResultXavc4kProfileSettings(array $json): Xavc4kProfileSettings
    {
        return new Xavc4kProfileSettings([
            'BitrateClass' => isset($json['bitrateClass']) ? (!Xavc4kProfileBitrateClass::exists((string) $json['bitrateClass']) ? Xavc4kProfileBitrateClass::UNKNOWN_TO_SDK : (string) $json['bitrateClass']) : null,
            'CodecProfile' => isset($json['codecProfile']) ? (!Xavc4kProfileCodecProfile::exists((string) $json['codecProfile']) ? Xavc4kProfileCodecProfile::UNKNOWN_TO_SDK : (string) $json['codecProfile']) : null,
            'FlickerAdaptiveQuantization' => isset($json['flickerAdaptiveQuantization']) ? (!XavcFlickerAdaptiveQuantization::exists((string) $json['flickerAdaptiveQuantization']) ? XavcFlickerAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['flickerAdaptiveQuantization']) : null,
            'GopBReference' => isset($json['gopBReference']) ? (!XavcGopBReference::exists((string) $json['gopBReference']) ? XavcGopBReference::UNKNOWN_TO_SDK : (string) $json['gopBReference']) : null,
            'GopClosedCadence' => isset($json['gopClosedCadence']) ? (int) $json['gopClosedCadence'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (!Xavc4kProfileQualityTuningLevel::exists((string) $json['qualityTuningLevel']) ? Xavc4kProfileQualityTuningLevel::UNKNOWN_TO_SDK : (string) $json['qualityTuningLevel']) : null,
            'Slices' => isset($json['slices']) ? (int) $json['slices'] : null,
        ]);
    }

    private function populateResultXavcHdIntraCbgProfileSettings(array $json): XavcHdIntraCbgProfileSettings
    {
        return new XavcHdIntraCbgProfileSettings([
            'XavcClass' => isset($json['xavcClass']) ? (!XavcHdIntraCbgProfileClass::exists((string) $json['xavcClass']) ? XavcHdIntraCbgProfileClass::UNKNOWN_TO_SDK : (string) $json['xavcClass']) : null,
        ]);
    }

    private function populateResultXavcHdProfileSettings(array $json): XavcHdProfileSettings
    {
        return new XavcHdProfileSettings([
            'BitrateClass' => isset($json['bitrateClass']) ? (!XavcHdProfileBitrateClass::exists((string) $json['bitrateClass']) ? XavcHdProfileBitrateClass::UNKNOWN_TO_SDK : (string) $json['bitrateClass']) : null,
            'FlickerAdaptiveQuantization' => isset($json['flickerAdaptiveQuantization']) ? (!XavcFlickerAdaptiveQuantization::exists((string) $json['flickerAdaptiveQuantization']) ? XavcFlickerAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['flickerAdaptiveQuantization']) : null,
            'GopBReference' => isset($json['gopBReference']) ? (!XavcGopBReference::exists((string) $json['gopBReference']) ? XavcGopBReference::UNKNOWN_TO_SDK : (string) $json['gopBReference']) : null,
            'GopClosedCadence' => isset($json['gopClosedCadence']) ? (int) $json['gopClosedCadence'] : null,
            'HrdBufferSize' => isset($json['hrdBufferSize']) ? (int) $json['hrdBufferSize'] : null,
            'InterlaceMode' => isset($json['interlaceMode']) ? (!XavcInterlaceMode::exists((string) $json['interlaceMode']) ? XavcInterlaceMode::UNKNOWN_TO_SDK : (string) $json['interlaceMode']) : null,
            'QualityTuningLevel' => isset($json['qualityTuningLevel']) ? (!XavcHdProfileQualityTuningLevel::exists((string) $json['qualityTuningLevel']) ? XavcHdProfileQualityTuningLevel::UNKNOWN_TO_SDK : (string) $json['qualityTuningLevel']) : null,
            'Slices' => isset($json['slices']) ? (int) $json['slices'] : null,
            'Telecine' => isset($json['telecine']) ? (!XavcHdProfileTelecine::exists((string) $json['telecine']) ? XavcHdProfileTelecine::UNKNOWN_TO_SDK : (string) $json['telecine']) : null,
        ]);
    }

    private function populateResultXavcSettings(array $json): XavcSettings
    {
        return new XavcSettings([
            'AdaptiveQuantization' => isset($json['adaptiveQuantization']) ? (!XavcAdaptiveQuantization::exists((string) $json['adaptiveQuantization']) ? XavcAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['adaptiveQuantization']) : null,
            'EntropyEncoding' => isset($json['entropyEncoding']) ? (!XavcEntropyEncoding::exists((string) $json['entropyEncoding']) ? XavcEntropyEncoding::UNKNOWN_TO_SDK : (string) $json['entropyEncoding']) : null,
            'FramerateControl' => isset($json['framerateControl']) ? (!XavcFramerateControl::exists((string) $json['framerateControl']) ? XavcFramerateControl::UNKNOWN_TO_SDK : (string) $json['framerateControl']) : null,
            'FramerateConversionAlgorithm' => isset($json['framerateConversionAlgorithm']) ? (!XavcFramerateConversionAlgorithm::exists((string) $json['framerateConversionAlgorithm']) ? XavcFramerateConversionAlgorithm::UNKNOWN_TO_SDK : (string) $json['framerateConversionAlgorithm']) : null,
            'FramerateDenominator' => isset($json['framerateDenominator']) ? (int) $json['framerateDenominator'] : null,
            'FramerateNumerator' => isset($json['framerateNumerator']) ? (int) $json['framerateNumerator'] : null,
            'PerFrameMetrics' => !isset($json['perFrameMetrics']) ? null : $this->populateResult__listOfFrameMetricType($json['perFrameMetrics']),
            'Profile' => isset($json['profile']) ? (!XavcProfile::exists((string) $json['profile']) ? XavcProfile::UNKNOWN_TO_SDK : (string) $json['profile']) : null,
            'SlowPal' => isset($json['slowPal']) ? (!XavcSlowPal::exists((string) $json['slowPal']) ? XavcSlowPal::UNKNOWN_TO_SDK : (string) $json['slowPal']) : null,
            'Softness' => isset($json['softness']) ? (int) $json['softness'] : null,
            'SpatialAdaptiveQuantization' => isset($json['spatialAdaptiveQuantization']) ? (!XavcSpatialAdaptiveQuantization::exists((string) $json['spatialAdaptiveQuantization']) ? XavcSpatialAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['spatialAdaptiveQuantization']) : null,
            'TemporalAdaptiveQuantization' => isset($json['temporalAdaptiveQuantization']) ? (!XavcTemporalAdaptiveQuantization::exists((string) $json['temporalAdaptiveQuantization']) ? XavcTemporalAdaptiveQuantization::UNKNOWN_TO_SDK : (string) $json['temporalAdaptiveQuantization']) : null,
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
                if (!AudioChannelTag::exists($a)) {
                    $a = AudioChannelTag::UNKNOWN_TO_SDK;
                }
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
                if (!FrameMetricType::exists($a)) {
                    $a = FrameMetricType::UNKNOWN_TO_SDK;
                }
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
                if (!HlsAdMarkers::exists($a)) {
                    $a = HlsAdMarkers::UNKNOWN_TO_SDK;
                }
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
                if (!TeletextPageType::exists($a)) {
                    $a = TeletextPageType::UNKNOWN_TO_SDK;
                }
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
