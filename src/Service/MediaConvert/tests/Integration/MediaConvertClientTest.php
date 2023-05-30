<?php

namespace AsyncAws\MediaConvert\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Input\CancelJobRequest;
use AsyncAws\MediaConvert\Input\CreateJobRequest;
use AsyncAws\MediaConvert\Input\DescribeEndpointsRequest;
use AsyncAws\MediaConvert\Input\GetJobRequest;
use AsyncAws\MediaConvert\Input\ListJobsRequest;
use AsyncAws\MediaConvert\MediaConvertClient;
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

class MediaConvertClientTest extends TestCase
{
    public function testCancelJob(): void
    {
        $client = $this->getClient();

        $input = new CancelJobRequest([
            'Id' => 'change me',
        ]);
        $result = $client->cancelJob($input);

        $result->resolve();
    }

    public function testCreateJob(): void
    {
        $client = $this->getClient();

        $input = new CreateJobRequest([
            'AccelerationSettings' => new AccelerationSettings([
                'Mode' => 'change me',
            ]),
            'BillingTagsSource' => 'change me',
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
                    'CopyProtectionAction' => 'change me',
                    'VchipAction' => 'change me',
                ]),
                'Inputs' => [new Input([
                    'AdvancedInputFilter' => 'change me',
                    'AdvancedInputFilterSettings' => new AdvancedInputFilterSettings([
                        'AddTexture' => 'change me',
                        'Sharpening' => 'change me',
                    ]),
                    'AudioSelectorGroups' => ['change me' => new AudioSelectorGroup([
                        'AudioSelectorNames' => ['change me'],
                    ])],
                    'AudioSelectors' => ['change me' => new AudioSelector([
                        'AudioDurationCorrection' => 'change me',
                        'CustomLanguageCode' => 'change me',
                        'DefaultSelection' => 'change me',
                        'ExternalAudioFileInput' => 'change me',
                        'HlsRenditionGroupSettings' => new HlsRenditionGroupSettings([
                            'RenditionGroupId' => 'change me',
                            'RenditionLanguageCode' => 'change me',
                            'RenditionName' => 'change me',
                        ]),
                        'LanguageCode' => 'change me',
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
                        'SelectorType' => 'change me',
                        'Tracks' => [1337],
                    ])],
                    'CaptionSelectors' => ['change me' => new CaptionSelector([
                        'CustomLanguageCode' => 'change me',
                        'LanguageCode' => 'change me',
                        'SourceSettings' => new CaptionSourceSettings([
                            'AncillarySourceSettings' => new AncillarySourceSettings([
                                'Convert608To708' => 'change me',
                                'SourceAncillaryChannelNumber' => 1337,
                                'TerminateCaptions' => 'change me',
                            ]),
                            'DvbSubSourceSettings' => new DvbSubSourceSettings([
                                'Pid' => 1337,
                            ]),
                            'EmbeddedSourceSettings' => new EmbeddedSourceSettings([
                                'Convert608To708' => 'change me',
                                'Source608ChannelNumber' => 1337,
                                'Source608TrackNumber' => 1337,
                                'TerminateCaptions' => 'change me',
                            ]),
                            'FileSourceSettings' => new FileSourceSettings([
                                'Convert608To708' => 'change me',
                                'ConvertPaintToPop' => 'change me',
                                'Framerate' => new CaptionSourceFramerate([
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                ]),
                                'SourceFile' => 'change me',
                                'TimeDelta' => 1337,
                                'TimeDeltaUnits' => 'change me',
                            ]),
                            'SourceType' => 'change me',
                            'TeletextSourceSettings' => new TeletextSourceSettings([
                                'PageNumber' => 'change me',
                            ]),
                            'TrackSourceSettings' => new TrackSourceSettings([
                                'TrackNumber' => 1337,
                            ]),
                            'WebvttHlsSourceSettings' => new WebvttHlsSourceSettings([
                                'RenditionGroupId' => 'change me',
                                'RenditionLanguageCode' => 'change me',
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
                    'DeblockFilter' => 'change me',
                    'DecryptionSettings' => new InputDecryptionSettings([
                        'DecryptionMode' => 'change me',
                        'EncryptedDecryptionKey' => 'change me',
                        'InitializationVector' => 'change me',
                        'KmsKeyRegion' => 'change me',
                    ]),
                    'DenoiseFilter' => 'change me',
                    'DolbyVisionMetadataXml' => 'change me',
                    'FileInput' => 'change me',
                    'FilterEnable' => 'change me',
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
                    'InputScanType' => 'change me',
                    'Position' => new Rectangle([
                        'Height' => 1337,
                        'Width' => 1337,
                        'X' => 1337,
                        'Y' => 1337,
                    ]),
                    'ProgramNumber' => 1337,
                    'PsiControl' => 'change me',
                    'SupplementalImps' => ['change me'],
                    'TimecodeSource' => 'change me',
                    'TimecodeStart' => 'change me',
                    'VideoGenerator' => new InputVideoGenerator([
                        'Duration' => 1337,
                    ]),
                    'VideoSelector' => new VideoSelector([
                        'AlphaBehavior' => 'change me',
                        'ColorSpace' => 'change me',
                        'ColorSpaceUsage' => 'change me',
                        'EmbeddedTimecodeOverride' => 'change me',
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
                        'PadVideo' => 'change me',
                        'Pid' => 1337,
                        'ProgramNumber' => 1337,
                        'Rotate' => 'change me',
                        'SampleRange' => 'change me',
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
                    'InsertionMode' => 'change me',
                    'Offset' => new MotionImageInsertionOffset([
                        'ImageX' => 1337,
                        'ImageY' => 1337,
                    ]),
                    'Playback' => 'change me',
                    'StartTime' => 'change me',
                ]),
                'NielsenConfiguration' => new NielsenConfiguration([
                    'BreakoutCode' => 1337,
                    'DistributorId' => 'change me',
                ]),
                'NielsenNonLinearWatermark' => new NielsenNonLinearWatermarkSettings([
                    'ActiveWatermarkProcess' => 'change me',
                    'AdiFilename' => 'change me',
                    'AssetId' => 'change me',
                    'AssetName' => 'change me',
                    'CbetSourceId' => 'change me',
                    'EpisodeId' => 'change me',
                    'MetadataDestination' => 'change me',
                    'SourceId' => 1337,
                    'SourceWatermarkStatus' => 'change me',
                    'TicServerUrl' => 'change me',
                    'UniqueTicPerAudioTrack' => 'change me',
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
                                    'Required' => 'change me',
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
                                'Type' => 'change me',
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
                            'ClientCache' => 'change me',
                            'CodecSpecification' => 'change me',
                            'DashManifestStyle' => 'change me',
                            'Destination' => 'change me',
                            'DestinationSettings' => new DestinationSettings([
                                'S3Settings' => new S3DestinationSettings([
                                    'AccessControl' => new S3DestinationAccessControl([
                                        'CannedAcl' => 'change me',
                                    ]),
                                    'Encryption' => new S3EncryptionSettings([
                                        'EncryptionType' => 'change me',
                                        'KmsEncryptionContext' => 'change me',
                                        'KmsKeyArn' => 'change me',
                                    ]),
                                ]),
                            ]),
                            'Encryption' => new CmafEncryptionSettings([
                                'ConstantInitializationVector' => 'change me',
                                'EncryptionMethod' => 'change me',
                                'InitializationVectorInManifest' => 'change me',
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
                                'Type' => 'change me',
                            ]),
                            'FragmentLength' => 1337,
                            'ImageBasedTrickPlay' => 'change me',
                            'ImageBasedTrickPlaySettings' => new CmafImageBasedTrickPlaySettings([
                                'IntervalCadence' => 'change me',
                                'ThumbnailHeight' => 1337,
                                'ThumbnailInterval' => 1337,
                                'ThumbnailWidth' => 1337,
                                'TileHeight' => 1337,
                                'TileWidth' => 1337,
                            ]),
                            'ManifestCompression' => 'change me',
                            'ManifestDurationFormat' => 'change me',
                            'MinBufferTime' => 1337,
                            'MinFinalSegmentLength' => 1337,
                            'MpdManifestBandwidthType' => 'change me',
                            'MpdProfile' => 'change me',
                            'PtsOffsetHandlingForBFrames' => 'change me',
                            'SegmentControl' => 'change me',
                            'SegmentLength' => 1337,
                            'SegmentLengthControl' => 'change me',
                            'StreamInfResolution' => 'change me',
                            'TargetDurationCompatibilityMode' => 'change me',
                            'VideoCompositionOffsets' => 'change me',
                            'WriteDashManifest' => 'change me',
                            'WriteHlsManifest' => 'change me',
                            'WriteSegmentTimelineInRepresentation' => 'change me',
                        ]),
                        'DashIsoGroupSettings' => new DashIsoGroupSettings([
                            'AdditionalManifests' => [new DashAdditionalManifest([
                                'ManifestNameModifier' => 'change me',
                                'SelectedOutputs' => ['change me'],
                            ])],
                            'AudioChannelConfigSchemeIdUri' => 'change me',
                            'BaseUrl' => 'change me',
                            'DashManifestStyle' => 'change me',
                            'Destination' => 'change me',
                            'DestinationSettings' => new DestinationSettings([
                                'S3Settings' => new S3DestinationSettings([
                                    'AccessControl' => new S3DestinationAccessControl([
                                        'CannedAcl' => 'change me',
                                    ]),
                                    'Encryption' => new S3EncryptionSettings([
                                        'EncryptionType' => 'change me',
                                        'KmsEncryptionContext' => 'change me',
                                        'KmsKeyArn' => 'change me',
                                    ]),
                                ]),
                            ]),
                            'Encryption' => new DashIsoEncryptionSettings([
                                'PlaybackDeviceCompatibility' => 'change me',
                                'SpekeKeyProvider' => new SpekeKeyProvider([
                                    'CertificateArn' => 'change me',
                                    'ResourceId' => 'change me',
                                    'SystemIds' => ['change me'],
                                    'Url' => 'change me',
                                ]),
                            ]),
                            'FragmentLength' => 1337,
                            'HbbtvCompliance' => 'change me',
                            'ImageBasedTrickPlay' => 'change me',
                            'ImageBasedTrickPlaySettings' => new DashIsoImageBasedTrickPlaySettings([
                                'IntervalCadence' => 'change me',
                                'ThumbnailHeight' => 1337,
                                'ThumbnailInterval' => 1337,
                                'ThumbnailWidth' => 1337,
                                'TileHeight' => 1337,
                                'TileWidth' => 1337,
                            ]),
                            'MinBufferTime' => 1337,
                            'MinFinalSegmentLength' => 1337,
                            'MpdManifestBandwidthType' => 'change me',
                            'MpdProfile' => 'change me',
                            'PtsOffsetHandlingForBFrames' => 'change me',
                            'SegmentControl' => 'change me',
                            'SegmentLength' => 1337,
                            'SegmentLengthControl' => 'change me',
                            'VideoCompositionOffsets' => 'change me',
                            'WriteSegmentTimelineInRepresentation' => 'change me',
                        ]),
                        'FileGroupSettings' => new FileGroupSettings([
                            'Destination' => 'change me',
                            'DestinationSettings' => new DestinationSettings([
                                'S3Settings' => new S3DestinationSettings([
                                    'AccessControl' => new S3DestinationAccessControl([
                                        'CannedAcl' => 'change me',
                                    ]),
                                    'Encryption' => new S3EncryptionSettings([
                                        'EncryptionType' => 'change me',
                                        'KmsEncryptionContext' => 'change me',
                                        'KmsKeyArn' => 'change me',
                                    ]),
                                ]),
                            ]),
                        ]),
                        'HlsGroupSettings' => new HlsGroupSettings([
                            'AdMarkers' => ['change me'],
                            'AdditionalManifests' => [new HlsAdditionalManifest([
                                'ManifestNameModifier' => 'change me',
                                'SelectedOutputs' => ['change me'],
                            ])],
                            'AudioOnlyHeader' => 'change me',
                            'BaseUrl' => 'change me',
                            'CaptionLanguageMappings' => [new HlsCaptionLanguageMapping([
                                'CaptionChannel' => 1337,
                                'CustomLanguageCode' => 'change me',
                                'LanguageCode' => 'change me',
                                'LanguageDescription' => 'change me',
                            ])],
                            'CaptionLanguageSetting' => 'change me',
                            'CaptionSegmentLengthControl' => 'change me',
                            'ClientCache' => 'change me',
                            'CodecSpecification' => 'change me',
                            'Destination' => 'change me',
                            'DestinationSettings' => new DestinationSettings([
                                'S3Settings' => new S3DestinationSettings([
                                    'AccessControl' => new S3DestinationAccessControl([
                                        'CannedAcl' => 'change me',
                                    ]),
                                    'Encryption' => new S3EncryptionSettings([
                                        'EncryptionType' => 'change me',
                                        'KmsEncryptionContext' => 'change me',
                                        'KmsKeyArn' => 'change me',
                                    ]),
                                ]),
                            ]),
                            'DirectoryStructure' => 'change me',
                            'Encryption' => new HlsEncryptionSettings([
                                'ConstantInitializationVector' => 'change me',
                                'EncryptionMethod' => 'change me',
                                'InitializationVectorInManifest' => 'change me',
                                'OfflineEncrypted' => 'change me',
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
                                'Type' => 'change me',
                            ]),
                            'ImageBasedTrickPlay' => 'change me',
                            'ImageBasedTrickPlaySettings' => new HlsImageBasedTrickPlaySettings([
                                'IntervalCadence' => 'change me',
                                'ThumbnailHeight' => 1337,
                                'ThumbnailInterval' => 1337,
                                'ThumbnailWidth' => 1337,
                                'TileHeight' => 1337,
                                'TileWidth' => 1337,
                            ]),
                            'ManifestCompression' => 'change me',
                            'ManifestDurationFormat' => 'change me',
                            'MinFinalSegmentLength' => 1337,
                            'MinSegmentLength' => 1337,
                            'OutputSelection' => 'change me',
                            'ProgramDateTime' => 'change me',
                            'ProgramDateTimePeriod' => 1337,
                            'SegmentControl' => 'change me',
                            'SegmentLength' => 1337,
                            'SegmentLengthControl' => 'change me',
                            'SegmentsPerSubdirectory' => 1337,
                            'StreamInfResolution' => 'change me',
                            'TargetDurationCompatibilityMode' => 'change me',
                            'TimedMetadataId3Frame' => 'change me',
                            'TimedMetadataId3Period' => 1337,
                            'TimestampDeltaMilliseconds' => 1337,
                        ]),
                        'MsSmoothGroupSettings' => new MsSmoothGroupSettings([
                            'AdditionalManifests' => [new MsSmoothAdditionalManifest([
                                'ManifestNameModifier' => 'change me',
                                'SelectedOutputs' => ['change me'],
                            ])],
                            'AudioDeduplication' => 'change me',
                            'Destination' => 'change me',
                            'DestinationSettings' => new DestinationSettings([
                                'S3Settings' => new S3DestinationSettings([
                                    'AccessControl' => new S3DestinationAccessControl([
                                        'CannedAcl' => 'change me',
                                    ]),
                                    'Encryption' => new S3EncryptionSettings([
                                        'EncryptionType' => 'change me',
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
                            'FragmentLengthControl' => 'change me',
                            'ManifestEncoding' => 'change me',
                        ]),
                        'Type' => 'change me',
                    ]),
                    'Outputs' => [new Output([
                        'AudioDescriptions' => [new AudioDescription([
                            'AudioChannelTaggingSettings' => new AudioChannelTaggingSettings([
                                'ChannelTag' => 'change me',
                            ]),
                            'AudioNormalizationSettings' => new AudioNormalizationSettings([
                                'Algorithm' => 'change me',
                                'AlgorithmControl' => 'change me',
                                'CorrectionGateLevel' => 1337,
                                'LoudnessLogging' => 'change me',
                                'PeakCalculation' => 'change me',
                                'TargetLkfs' => 1337,
                                'TruePeakLimiterThreshold' => 1337,
                            ]),
                            'AudioSourceName' => 'change me',
                            'AudioType' => 1337,
                            'AudioTypeControl' => 'change me',
                            'CodecSettings' => new AudioCodecSettings([
                                'AacSettings' => new AacSettings([
                                    'AudioDescriptionBroadcasterMix' => 'change me',
                                    'Bitrate' => 1337,
                                    'CodecProfile' => 'change me',
                                    'CodingMode' => 'change me',
                                    'RateControlMode' => 'change me',
                                    'RawFormat' => 'change me',
                                    'SampleRate' => 1337,
                                    'Specification' => 'change me',
                                    'VbrQuality' => 'change me',
                                ]),
                                'Ac3Settings' => new Ac3Settings([
                                    'Bitrate' => 1337,
                                    'BitstreamMode' => 'change me',
                                    'CodingMode' => 'change me',
                                    'Dialnorm' => 1337,
                                    'DynamicRangeCompressionLine' => 'change me',
                                    'DynamicRangeCompressionProfile' => 'change me',
                                    'DynamicRangeCompressionRf' => 'change me',
                                    'LfeFilter' => 'change me',
                                    'MetadataControl' => 'change me',
                                    'SampleRate' => 1337,
                                ]),
                                'AiffSettings' => new AiffSettings([
                                    'BitDepth' => 1337,
                                    'Channels' => 1337,
                                    'SampleRate' => 1337,
                                ]),
                                'Codec' => 'change me',
                                'Eac3AtmosSettings' => new Eac3AtmosSettings([
                                    'Bitrate' => 1337,
                                    'BitstreamMode' => 'change me',
                                    'CodingMode' => 'change me',
                                    'DialogueIntelligence' => 'change me',
                                    'DownmixControl' => 'change me',
                                    'DynamicRangeCompressionLine' => 'change me',
                                    'DynamicRangeCompressionRf' => 'change me',
                                    'DynamicRangeControl' => 'change me',
                                    'LoRoCenterMixLevel' => 1337,
                                    'LoRoSurroundMixLevel' => 1337,
                                    'LtRtCenterMixLevel' => 1337,
                                    'LtRtSurroundMixLevel' => 1337,
                                    'MeteringMode' => 'change me',
                                    'SampleRate' => 1337,
                                    'SpeechThreshold' => 1337,
                                    'StereoDownmix' => 'change me',
                                    'SurroundExMode' => 'change me',
                                ]),
                                'Eac3Settings' => new Eac3Settings([
                                    'AttenuationControl' => 'change me',
                                    'Bitrate' => 1337,
                                    'BitstreamMode' => 'change me',
                                    'CodingMode' => 'change me',
                                    'DcFilter' => 'change me',
                                    'Dialnorm' => 1337,
                                    'DynamicRangeCompressionLine' => 'change me',
                                    'DynamicRangeCompressionRf' => 'change me',
                                    'LfeControl' => 'change me',
                                    'LfeFilter' => 'change me',
                                    'LoRoCenterMixLevel' => 1337,
                                    'LoRoSurroundMixLevel' => 1337,
                                    'LtRtCenterMixLevel' => 1337,
                                    'LtRtSurroundMixLevel' => 1337,
                                    'MetadataControl' => 'change me',
                                    'PassthroughControl' => 'change me',
                                    'PhaseControl' => 'change me',
                                    'SampleRate' => 1337,
                                    'StereoDownmix' => 'change me',
                                    'SurroundExMode' => 'change me',
                                    'SurroundMode' => 'change me',
                                ]),
                                'Mp2Settings' => new Mp2Settings([
                                    'Bitrate' => 1337,
                                    'Channels' => 1337,
                                    'SampleRate' => 1337,
                                ]),
                                'Mp3Settings' => new Mp3Settings([
                                    'Bitrate' => 1337,
                                    'Channels' => 1337,
                                    'RateControlMode' => 'change me',
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
                                    'Format' => 'change me',
                                    'SampleRate' => 1337,
                                ]),
                            ]),
                            'CustomLanguageCode' => 'change me',
                            'LanguageCode' => 'change me',
                            'LanguageCodeControl' => 'change me',
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
                                    'Alignment' => 'change me',
                                    'ApplyFontColor' => 'change me',
                                    'BackgroundColor' => 'change me',
                                    'BackgroundOpacity' => 1337,
                                    'FallbackFont' => 'change me',
                                    'FontColor' => 'change me',
                                    'FontOpacity' => 1337,
                                    'FontResolution' => 1337,
                                    'FontScript' => 'change me',
                                    'FontSize' => 1337,
                                    'HexFontColor' => 'change me',
                                    'OutlineColor' => 'change me',
                                    'OutlineSize' => 1337,
                                    'ShadowColor' => 'change me',
                                    'ShadowOpacity' => 1337,
                                    'ShadowXOffset' => 1337,
                                    'ShadowYOffset' => 1337,
                                    'StylePassthrough' => 'change me',
                                    'TeletextSpacing' => 'change me',
                                    'XPosition' => 1337,
                                    'YPosition' => 1337,
                                ]),
                                'DestinationType' => 'change me',
                                'DvbSubDestinationSettings' => new DvbSubDestinationSettings([
                                    'Alignment' => 'change me',
                                    'ApplyFontColor' => 'change me',
                                    'BackgroundColor' => 'change me',
                                    'BackgroundOpacity' => 1337,
                                    'DdsHandling' => 'change me',
                                    'DdsXCoordinate' => 1337,
                                    'DdsYCoordinate' => 1337,
                                    'FallbackFont' => 'change me',
                                    'FontColor' => 'change me',
                                    'FontOpacity' => 1337,
                                    'FontResolution' => 1337,
                                    'FontScript' => 'change me',
                                    'FontSize' => 1337,
                                    'Height' => 1337,
                                    'HexFontColor' => 'change me',
                                    'OutlineColor' => 'change me',
                                    'OutlineSize' => 1337,
                                    'ShadowColor' => 'change me',
                                    'ShadowOpacity' => 1337,
                                    'ShadowXOffset' => 1337,
                                    'ShadowYOffset' => 1337,
                                    'StylePassthrough' => 'change me',
                                    'SubtitlingType' => 'change me',
                                    'TeletextSpacing' => 'change me',
                                    'Width' => 1337,
                                    'XPosition' => 1337,
                                    'YPosition' => 1337,
                                ]),
                                'EmbeddedDestinationSettings' => new EmbeddedDestinationSettings([
                                    'Destination608ChannelNumber' => 1337,
                                    'Destination708ServiceNumber' => 1337,
                                ]),
                                'ImscDestinationSettings' => new ImscDestinationSettings([
                                    'Accessibility' => 'change me',
                                    'StylePassthrough' => 'change me',
                                ]),
                                'SccDestinationSettings' => new SccDestinationSettings([
                                    'Framerate' => 'change me',
                                ]),
                                'SrtDestinationSettings' => new SrtDestinationSettings([
                                    'StylePassthrough' => 'change me',
                                ]),
                                'TeletextDestinationSettings' => new TeletextDestinationSettings([
                                    'PageNumber' => 'change me',
                                    'PageTypes' => ['change me'],
                                ]),
                                'TtmlDestinationSettings' => new TtmlDestinationSettings([
                                    'StylePassthrough' => 'change me',
                                ]),
                                'WebvttDestinationSettings' => new WebvttDestinationSettings([
                                    'Accessibility' => 'change me',
                                    'StylePassthrough' => 'change me',
                                ]),
                            ]),
                            'LanguageCode' => 'change me',
                            'LanguageDescription' => 'change me',
                        ])],
                        'ContainerSettings' => new ContainerSettings([
                            'CmfcSettings' => new CmfcSettings([
                                'AudioDuration' => 'change me',
                                'AudioGroupId' => 'change me',
                                'AudioRenditionSets' => 'change me',
                                'AudioTrackType' => 'change me',
                                'DescriptiveVideoServiceFlag' => 'change me',
                                'IFrameOnlyManifest' => 'change me',
                                'KlvMetadata' => 'change me',
                                'ManifestMetadataSignaling' => 'change me',
                                'Scte35Esam' => 'change me',
                                'Scte35Source' => 'change me',
                                'TimedMetadata' => 'change me',
                                'TimedMetadataBoxVersion' => 'change me',
                                'TimedMetadataSchemeIdUri' => 'change me',
                                'TimedMetadataValue' => 'change me',
                            ]),
                            'Container' => 'change me',
                            'F4vSettings' => new F4vSettings([
                                'MoovPlacement' => 'change me',
                            ]),
                            'M2tsSettings' => new M2tsSettings([
                                'AudioBufferModel' => 'change me',
                                'AudioDuration' => 'change me',
                                'AudioFramesPerPes' => 1337,
                                'AudioPids' => [1337],
                                'Bitrate' => 1337,
                                'BufferModel' => 'change me',
                                'DataPTSControl' => 'change me',
                                'DvbNitSettings' => new DvbNitSettings([
                                    'NetworkId' => 1337,
                                    'NetworkName' => 'change me',
                                    'NitInterval' => 1337,
                                ]),
                                'DvbSdtSettings' => new DvbSdtSettings([
                                    'OutputSdt' => 'change me',
                                    'SdtInterval' => 1337,
                                    'ServiceName' => 'change me',
                                    'ServiceProviderName' => 'change me',
                                ]),
                                'DvbSubPids' => [1337],
                                'DvbTdtSettings' => new DvbTdtSettings([
                                    'TdtInterval' => 1337,
                                ]),
                                'DvbTeletextPid' => 1337,
                                'EbpAudioInterval' => 'change me',
                                'EbpPlacement' => 'change me',
                                'EsRateInPes' => 'change me',
                                'ForceTsVideoEbpOrder' => 'change me',
                                'FragmentTime' => 1337,
                                'KlvMetadata' => 'change me',
                                'MaxPcrInterval' => 1337,
                                'MinEbpInterval' => 1337,
                                'NielsenId3' => 'change me',
                                'NullPacketBitrate' => 1337,
                                'PatInterval' => 1337,
                                'PcrControl' => 'change me',
                                'PcrPid' => 1337,
                                'PmtInterval' => 1337,
                                'PmtPid' => 1337,
                                'PrivateMetadataPid' => 1337,
                                'ProgramNumber' => 1337,
                                'RateMode' => 'change me',
                                'Scte35Esam' => new M2tsScte35Esam([
                                    'Scte35EsamPid' => 1337,
                                ]),
                                'Scte35Pid' => 1337,
                                'Scte35Source' => 'change me',
                                'SegmentationMarkers' => 'change me',
                                'SegmentationStyle' => 'change me',
                                'SegmentationTime' => 1337,
                                'TimedMetadataPid' => 1337,
                                'TransportStreamId' => 1337,
                                'VideoPid' => 1337,
                            ]),
                            'M3u8Settings' => new M3u8Settings([
                                'AudioDuration' => 'change me',
                                'AudioFramesPerPes' => 1337,
                                'AudioPids' => [1337],
                                'DataPTSControl' => 'change me',
                                'MaxPcrInterval' => 1337,
                                'NielsenId3' => 'change me',
                                'PatInterval' => 1337,
                                'PcrControl' => 'change me',
                                'PcrPid' => 1337,
                                'PmtInterval' => 1337,
                                'PmtPid' => 1337,
                                'PrivateMetadataPid' => 1337,
                                'ProgramNumber' => 1337,
                                'Scte35Pid' => 1337,
                                'Scte35Source' => 'change me',
                                'TimedMetadata' => 'change me',
                                'TimedMetadataPid' => 1337,
                                'TransportStreamId' => 1337,
                                'VideoPid' => 1337,
                            ]),
                            'MovSettings' => new MovSettings([
                                'ClapAtom' => 'change me',
                                'CslgAtom' => 'change me',
                                'Mpeg2FourCCControl' => 'change me',
                                'PaddingControl' => 'change me',
                                'Reference' => 'change me',
                            ]),
                            'Mp4Settings' => new Mp4Settings([
                                'AudioDuration' => 'change me',
                                'CslgAtom' => 'change me',
                                'CttsVersion' => 1337,
                                'FreeSpaceBox' => 'change me',
                                'MoovPlacement' => 'change me',
                                'Mp4MajorBrand' => 'change me',
                            ]),
                            'MpdSettings' => new MpdSettings([
                                'AccessibilityCaptionHints' => 'change me',
                                'AudioDuration' => 'change me',
                                'CaptionContainerType' => 'change me',
                                'KlvMetadata' => 'change me',
                                'ManifestMetadataSignaling' => 'change me',
                                'Scte35Esam' => 'change me',
                                'Scte35Source' => 'change me',
                                'TimedMetadata' => 'change me',
                                'TimedMetadataBoxVersion' => 'change me',
                                'TimedMetadataSchemeIdUri' => 'change me',
                                'TimedMetadataValue' => 'change me',
                            ]),
                            'MxfSettings' => new MxfSettings([
                                'AfdSignaling' => 'change me',
                                'Profile' => 'change me',
                                'XavcProfileSettings' => new MxfXavcProfileSettings([
                                    'DurationMode' => 'change me',
                                    'MaxAncDataSize' => 1337,
                                ]),
                            ]),
                        ]),
                        'Extension' => 'change me',
                        'NameModifier' => 'change me',
                        'OutputSettings' => new OutputSettings([
                            'HlsSettings' => new HlsSettings([
                                'AudioGroupId' => 'change me',
                                'AudioOnlyContainer' => 'change me',
                                'AudioRenditionSets' => 'change me',
                                'AudioTrackType' => 'change me',
                                'DescriptiveVideoServiceFlag' => 'change me',
                                'IFrameOnlyManifest' => 'change me',
                                'SegmentModifier' => 'change me',
                            ]),
                        ]),
                        'Preset' => 'change me',
                        'VideoDescription' => new VideoDescription([
                            'AfdSignaling' => 'change me',
                            'AntiAlias' => 'change me',
                            'CodecSettings' => new VideoCodecSettings([
                                'Av1Settings' => new Av1Settings([
                                    'AdaptiveQuantization' => 'change me',
                                    'BitDepth' => 'change me',
                                    'FramerateControl' => 'change me',
                                    'FramerateConversionAlgorithm' => 'change me',
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopSize' => 1337,
                                    'MaxBitrate' => 1337,
                                    'NumberBFramesBetweenReferenceFrames' => 1337,
                                    'QvbrSettings' => new Av1QvbrSettings([
                                        'QvbrQualityLevel' => 1337,
                                        'QvbrQualityLevelFineTune' => 1337,
                                    ]),
                                    'RateControlMode' => 'change me',
                                    'Slices' => 1337,
                                    'SpatialAdaptiveQuantization' => 'change me',
                                ]),
                                'AvcIntraSettings' => new AvcIntraSettings([
                                    'AvcIntraClass' => 'change me',
                                    'AvcIntraUhdSettings' => new AvcIntraUhdSettings([
                                        'QualityTuningLevel' => 'change me',
                                    ]),
                                    'FramerateControl' => 'change me',
                                    'FramerateConversionAlgorithm' => 'change me',
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'InterlaceMode' => 'change me',
                                    'ScanTypeConversionMode' => 'change me',
                                    'SlowPal' => 'change me',
                                    'Telecine' => 'change me',
                                ]),
                                'Codec' => 'change me',
                                'FrameCaptureSettings' => new FrameCaptureSettings([
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'MaxCaptures' => 1337,
                                    'Quality' => 1337,
                                ]),
                                'H264Settings' => new H264Settings([
                                    'AdaptiveQuantization' => 'change me',
                                    'BandwidthReductionFilter' => new BandwidthReductionFilter([
                                        'Sharpening' => 'change me',
                                        'Strength' => 'change me',
                                    ]),
                                    'Bitrate' => 1337,
                                    'CodecLevel' => 'change me',
                                    'CodecProfile' => 'change me',
                                    'DynamicSubGop' => 'change me',
                                    'EntropyEncoding' => 'change me',
                                    'FieldEncoding' => 'change me',
                                    'FlickerAdaptiveQuantization' => 'change me',
                                    'FramerateControl' => 'change me',
                                    'FramerateConversionAlgorithm' => 'change me',
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopBReference' => 'change me',
                                    'GopClosedCadence' => 1337,
                                    'GopSize' => 1337,
                                    'GopSizeUnits' => 'change me',
                                    'HrdBufferFinalFillPercentage' => 1337,
                                    'HrdBufferInitialFillPercentage' => 1337,
                                    'HrdBufferSize' => 1337,
                                    'InterlaceMode' => 'change me',
                                    'MaxBitrate' => 1337,
                                    'MinIInterval' => 1337,
                                    'NumberBFramesBetweenReferenceFrames' => 1337,
                                    'NumberReferenceFrames' => 1337,
                                    'ParControl' => 'change me',
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'QualityTuningLevel' => 'change me',
                                    'QvbrSettings' => new H264QvbrSettings([
                                        'MaxAverageBitrate' => 1337,
                                        'QvbrQualityLevel' => 1337,
                                        'QvbrQualityLevelFineTune' => 1337,
                                    ]),
                                    'RateControlMode' => 'change me',
                                    'RepeatPps' => 'change me',
                                    'ScanTypeConversionMode' => 'change me',
                                    'SceneChangeDetect' => 'change me',
                                    'Slices' => 1337,
                                    'SlowPal' => 'change me',
                                    'Softness' => 1337,
                                    'SpatialAdaptiveQuantization' => 'change me',
                                    'Syntax' => 'change me',
                                    'Telecine' => 'change me',
                                    'TemporalAdaptiveQuantization' => 'change me',
                                    'UnregisteredSeiTimecode' => 'change me',
                                ]),
                                'H265Settings' => new H265Settings([
                                    'AdaptiveQuantization' => 'change me',
                                    'AlternateTransferFunctionSei' => 'change me',
                                    'Bitrate' => 1337,
                                    'CodecLevel' => 'change me',
                                    'CodecProfile' => 'change me',
                                    'DynamicSubGop' => 'change me',
                                    'FlickerAdaptiveQuantization' => 'change me',
                                    'FramerateControl' => 'change me',
                                    'FramerateConversionAlgorithm' => 'change me',
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopBReference' => 'change me',
                                    'GopClosedCadence' => 1337,
                                    'GopSize' => 1337,
                                    'GopSizeUnits' => 'change me',
                                    'HrdBufferFinalFillPercentage' => 1337,
                                    'HrdBufferInitialFillPercentage' => 1337,
                                    'HrdBufferSize' => 1337,
                                    'InterlaceMode' => 'change me',
                                    'MaxBitrate' => 1337,
                                    'MinIInterval' => 1337,
                                    'NumberBFramesBetweenReferenceFrames' => 1337,
                                    'NumberReferenceFrames' => 1337,
                                    'ParControl' => 'change me',
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'QualityTuningLevel' => 'change me',
                                    'QvbrSettings' => new H265QvbrSettings([
                                        'MaxAverageBitrate' => 1337,
                                        'QvbrQualityLevel' => 1337,
                                        'QvbrQualityLevelFineTune' => 1337,
                                    ]),
                                    'RateControlMode' => 'change me',
                                    'SampleAdaptiveOffsetFilterMode' => 'change me',
                                    'ScanTypeConversionMode' => 'change me',
                                    'SceneChangeDetect' => 'change me',
                                    'Slices' => 1337,
                                    'SlowPal' => 'change me',
                                    'SpatialAdaptiveQuantization' => 'change me',
                                    'Telecine' => 'change me',
                                    'TemporalAdaptiveQuantization' => 'change me',
                                    'TemporalIds' => 'change me',
                                    'Tiles' => 'change me',
                                    'UnregisteredSeiTimecode' => 'change me',
                                    'WriteMp4PackagingType' => 'change me',
                                ]),
                                'Mpeg2Settings' => new Mpeg2Settings([
                                    'AdaptiveQuantization' => 'change me',
                                    'Bitrate' => 1337,
                                    'CodecLevel' => 'change me',
                                    'CodecProfile' => 'change me',
                                    'DynamicSubGop' => 'change me',
                                    'FramerateControl' => 'change me',
                                    'FramerateConversionAlgorithm' => 'change me',
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopClosedCadence' => 1337,
                                    'GopSize' => 1337,
                                    'GopSizeUnits' => 'change me',
                                    'HrdBufferFinalFillPercentage' => 1337,
                                    'HrdBufferInitialFillPercentage' => 1337,
                                    'HrdBufferSize' => 1337,
                                    'InterlaceMode' => 'change me',
                                    'IntraDcPrecision' => 'change me',
                                    'MaxBitrate' => 1337,
                                    'MinIInterval' => 1337,
                                    'NumberBFramesBetweenReferenceFrames' => 1337,
                                    'ParControl' => 'change me',
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'QualityTuningLevel' => 'change me',
                                    'RateControlMode' => 'change me',
                                    'ScanTypeConversionMode' => 'change me',
                                    'SceneChangeDetect' => 'change me',
                                    'SlowPal' => 'change me',
                                    'Softness' => 1337,
                                    'SpatialAdaptiveQuantization' => 'change me',
                                    'Syntax' => 'change me',
                                    'Telecine' => 'change me',
                                    'TemporalAdaptiveQuantization' => 'change me',
                                ]),
                                'ProresSettings' => new ProresSettings([
                                    'ChromaSampling' => 'change me',
                                    'CodecProfile' => 'change me',
                                    'FramerateControl' => 'change me',
                                    'FramerateConversionAlgorithm' => 'change me',
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'InterlaceMode' => 'change me',
                                    'ParControl' => 'change me',
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'ScanTypeConversionMode' => 'change me',
                                    'SlowPal' => 'change me',
                                    'Telecine' => 'change me',
                                ]),
                                'Vc3Settings' => new Vc3Settings([
                                    'FramerateControl' => 'change me',
                                    'FramerateConversionAlgorithm' => 'change me',
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'InterlaceMode' => 'change me',
                                    'ScanTypeConversionMode' => 'change me',
                                    'SlowPal' => 'change me',
                                    'Telecine' => 'change me',
                                    'Vc3Class' => 'change me',
                                ]),
                                'Vp8Settings' => new Vp8Settings([
                                    'Bitrate' => 1337,
                                    'FramerateControl' => 'change me',
                                    'FramerateConversionAlgorithm' => 'change me',
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopSize' => 1337,
                                    'HrdBufferSize' => 1337,
                                    'MaxBitrate' => 1337,
                                    'ParControl' => 'change me',
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'QualityTuningLevel' => 'change me',
                                    'RateControlMode' => 'change me',
                                ]),
                                'Vp9Settings' => new Vp9Settings([
                                    'Bitrate' => 1337,
                                    'FramerateControl' => 'change me',
                                    'FramerateConversionAlgorithm' => 'change me',
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'GopSize' => 1337,
                                    'HrdBufferSize' => 1337,
                                    'MaxBitrate' => 1337,
                                    'ParControl' => 'change me',
                                    'ParDenominator' => 1337,
                                    'ParNumerator' => 1337,
                                    'QualityTuningLevel' => 'change me',
                                    'RateControlMode' => 'change me',
                                ]),
                                'XavcSettings' => new XavcSettings([
                                    'AdaptiveQuantization' => 'change me',
                                    'EntropyEncoding' => 'change me',
                                    'FramerateControl' => 'change me',
                                    'FramerateConversionAlgorithm' => 'change me',
                                    'FramerateDenominator' => 1337,
                                    'FramerateNumerator' => 1337,
                                    'Profile' => 'change me',
                                    'SlowPal' => 'change me',
                                    'Softness' => 1337,
                                    'SpatialAdaptiveQuantization' => 'change me',
                                    'TemporalAdaptiveQuantization' => 'change me',
                                    'Xavc4kIntraCbgProfileSettings' => new Xavc4kIntraCbgProfileSettings([
                                        'XavcClass' => 'change me',
                                    ]),
                                    'Xavc4kIntraVbrProfileSettings' => new Xavc4kIntraVbrProfileSettings([
                                        'XavcClass' => 'change me',
                                    ]),
                                    'Xavc4kProfileSettings' => new Xavc4kProfileSettings([
                                        'BitrateClass' => 'change me',
                                        'CodecProfile' => 'change me',
                                        'FlickerAdaptiveQuantization' => 'change me',
                                        'GopBReference' => 'change me',
                                        'GopClosedCadence' => 1337,
                                        'HrdBufferSize' => 1337,
                                        'QualityTuningLevel' => 'change me',
                                        'Slices' => 1337,
                                    ]),
                                    'XavcHdIntraCbgProfileSettings' => new XavcHdIntraCbgProfileSettings([
                                        'XavcClass' => 'change me',
                                    ]),
                                    'XavcHdProfileSettings' => new XavcHdProfileSettings([
                                        'BitrateClass' => 'change me',
                                        'FlickerAdaptiveQuantization' => 'change me',
                                        'GopBReference' => 'change me',
                                        'GopClosedCadence' => 1337,
                                        'HrdBufferSize' => 1337,
                                        'InterlaceMode' => 'change me',
                                        'QualityTuningLevel' => 'change me',
                                        'Slices' => 1337,
                                        'Telecine' => 'change me',
                                    ]),
                                ]),
                            ]),
                            'ColorMetadata' => 'change me',
                            'Crop' => new Rectangle([
                                'Height' => 1337,
                                'Width' => 1337,
                                'X' => 1337,
                                'Y' => 1337,
                            ]),
                            'DropFrameTimecode' => 'change me',
                            'FixedAfd' => 1337,
                            'Height' => 1337,
                            'Position' => new Rectangle([
                                'Height' => 1337,
                                'Width' => 1337,
                                'X' => 1337,
                                'Y' => 1337,
                            ]),
                            'RespondToAfd' => 'change me',
                            'ScalingBehavior' => 'change me',
                            'Sharpness' => 1337,
                            'TimecodeInsertion' => 'change me',
                            'VideoPreprocessors' => new VideoPreprocessor([
                                'ColorCorrector' => new ColorCorrector([
                                    'Brightness' => 1337,
                                    'ClipLimits' => new ClipLimits([
                                        'MaximumRGBTolerance' => 1337,
                                        'MaximumYUV' => 1337,
                                        'MinimumRGBTolerance' => 1337,
                                        'MinimumYUV' => 1337,
                                    ]),
                                    'ColorSpaceConversion' => 'change me',
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
                                    'HdrToSdrToneMapper' => 'change me',
                                    'Hue' => 1337,
                                    'SampleRangeConversion' => 'change me',
                                    'Saturation' => 1337,
                                    'SdrReferenceWhiteLevel' => 1337,
                                ]),
                                'Deinterlacer' => new Deinterlacer([
                                    'Algorithm' => 'change me',
                                    'Control' => 'change me',
                                    'Mode' => 'change me',
                                ]),
                                'DolbyVision' => new DolbyVision([
                                    'L6Metadata' => new DolbyVisionLevel6Metadata([
                                        'MaxCll' => 1337,
                                        'MaxFall' => 1337,
                                    ]),
                                    'L6Mode' => 'change me',
                                    'Mapping' => 'change me',
                                    'Profile' => 'change me',
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
                                    'Filter' => 'change me',
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
                                        'PostTemporalSharpening' => 'change me',
                                        'PostTemporalSharpeningStrength' => 'change me',
                                        'Speed' => 1337,
                                        'Strength' => 1337,
                                    ]),
                                ]),
                                'PartnerWatermarking' => new PartnerWatermarking([
                                    'NexguardFileMarkerSettings' => new NexGuardFileMarkerSettings([
                                        'License' => 'change me',
                                        'Payload' => 1337,
                                        'Preset' => 'change me',
                                        'Strength' => 'change me',
                                    ]),
                                ]),
                                'TimecodeBurnin' => new TimecodeBurnin([
                                    'FontSize' => 1337,
                                    'Position' => 'change me',
                                    'Prefix' => 'change me',
                                ]),
                            ]),
                            'Width' => 1337,
                        ]),
                    ])],
                ])],
                'TimecodeConfig' => new TimecodeConfig([
                    'Anchor' => 'change me',
                    'Source' => 'change me',
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
            'SimulateReservedQueue' => 'change me',
            'StatusUpdateInterval' => 'change me',
            'Tags' => ['change me' => 'change me'],
            'UserMetadata' => ['change me' => 'change me'],
        ]);
        $result = $client->createJob($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getJob());
    }

    public function testDescribeEndpoints(): void
    {
        $client = $this->getClient();

        $input = new DescribeEndpointsRequest([
            'MaxResults' => 1337,
            'Mode' => 'change me',
            'NextToken' => 'change me',
        ]);
        $result = $client->describeEndpoints($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getEndpoints());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testGetJob(): void
    {
        $client = $this->getClient();

        $input = new GetJobRequest([
            'Id' => 'change me',
        ]);
        $result = $client->getJob($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getJob());
    }

    public function testListJobs(): void
    {
        $client = $this->getClient();

        $input = new ListJobsRequest([
            'MaxResults' => 1337,
            'NextToken' => 'change me',
            'Order' => 'change me',
            'Queue' => 'change me',
            'Status' => 'change me',
        ]);
        $result = $client->listJobs($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getJobs());
        self::assertSame('changeIt', $result->getNextToken());
    }

    private function getClient(): MediaConvertClient
    {
        self::markTestSkipped('There is no docker image available for MediaConvert.');

        return new MediaConvertClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
