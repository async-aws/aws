<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\CmafClientCache;
use AsyncAws\MediaConvert\Enum\CmafCodecSpecification;
use AsyncAws\MediaConvert\Enum\CmafImageBasedTrickPlay;
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
use AsyncAws\MediaConvert\Enum\DashManifestStyle;

/**
 * Settings related to your CMAF output package. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/outputs-file-ABR.html.
 */
final class CmafGroupSettings
{
    /**
     * By default, the service creates one top-level .m3u8 HLS manifest and one top -level .mpd DASH manifest for each CMAF
     * output group in your job. These default manifests reference every output in the output group. To create additional
     * top-level manifests that reference a subset of the outputs in the output group, specify a list of them here. For each
     * additional manifest that you specify, the service creates one HLS manifest and one DASH manifest.
     *
     * @var CmafAdditionalManifest[]|null
     */
    private $additionalManifests;

    /**
     * A partial URI prefix that will be put in the manifest file at the top level BaseURL element. Can be used if streams
     * are delivered from a different URL than the manifest file.
     *
     * @var string|null
     */
    private $baseUrl;

    /**
     * Disable this setting only when your workflow requires the #EXT-X-ALLOW-CACHE:no tag. Otherwise, keep the default
     * value Enabled and control caching in your video distribution set up. For example, use the Cache-Control http header.
     *
     * @var CmafClientCache::*|null
     */
    private $clientCache;

    /**
     * Specification to use (RFC-6381 or the default RFC-4281) during m3u8 playlist generation.
     *
     * @var CmafCodecSpecification::*|null
     */
    private $codecSpecification;

    /**
     * Specify whether MediaConvert generates I-frame only video segments for DASH trick play, also known as trick mode.
     * When specified, the I-frame only video segments are included within an additional AdaptationSet in your DASH output
     * manifest. To generate I-frame only video segments: Enter a name as a text string, up to 256 character long. This name
     * is appended to the end of this output group's base filename, that you specify as part of your destination URI, and
     * used for the I-frame only video segment files. You may also include format identifiers. For more information, see:
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/using-variables-in-your-job-settings.html#using-settings-variables-with-streaming-outputs
     * To not generate I-frame only video segments: Leave blank.
     *
     * @var string|null
     */
    private $dashIframeTrickPlayNameModifier;

    /**
     * Specify how MediaConvert writes SegmentTimeline in your output DASH manifest. To write a SegmentTimeline in each
     * video Representation: Keep the default value, Basic. To write a common SegmentTimeline in the video AdaptationSet:
     * Choose Compact. Note that MediaConvert will still write a SegmentTimeline in any Representation that does not share a
     * common timeline. To write a video AdaptationSet for each different output framerate, and a common SegmentTimeline in
     * each AdaptationSet: Choose Distinct.
     *
     * @var DashManifestStyle::*|null
     */
    private $dashManifestStyle;

    /**
     * Use Destination to specify the S3 output location and the output filename base. Destination accepts format
     * identifiers. If you do not specify the base filename in the URI, the service will use the filename of the input file.
     * If your job has multiple inputs, the service uses the filename of the first input file.
     *
     * @var string|null
     */
    private $destination;

    /**
     * Settings associated with the destination. Will vary based on the type of destination.
     *
     * @var DestinationSettings|null
     */
    private $destinationSettings;

    /**
     * DRM settings.
     *
     * @var CmafEncryptionSettings|null
     */
    private $encryption;

    /**
     * Specify the length, in whole seconds, of the mp4 fragments. When you don't specify a value, MediaConvert defaults to
     * 2. Related setting: Use Fragment length control to specify whether the encoder enforces this value strictly.
     *
     * @var int|null
     */
    private $fragmentLength;

    /**
     * Specify whether MediaConvert generates images for trick play. Keep the default value, None, to not generate any
     * images. Choose Thumbnail to generate tiled thumbnails. Choose Thumbnail and full frame to generate tiled thumbnails
     * and full-resolution images of single frames. When you enable Write HLS manifest, MediaConvert creates a child
     * manifest for each set of images that you generate and adds corresponding entries to the parent manifest. When you
     * enable Write DASH manifest, MediaConvert adds an entry in the .mpd manifest for each set of images that you generate.
     * A common application for these images is Roku trick mode. The thumbnails and full-frame images that MediaConvert
     * creates with this feature are compatible with this Roku specification:
     * https://developer.roku.com/docs/developer-program/media-playback/trick-mode/hls-and-dash.md.
     *
     * @var CmafImageBasedTrickPlay::*|null
     */
    private $imageBasedTrickPlay;

    /**
     * Tile and thumbnail settings applicable when imageBasedTrickPlay is ADVANCED.
     *
     * @var CmafImageBasedTrickPlaySettings|null
     */
    private $imageBasedTrickPlaySettings;

    /**
     * When set to GZIP, compresses HLS playlist.
     *
     * @var CmafManifestCompression::*|null
     */
    private $manifestCompression;

    /**
     * Indicates whether the output manifest should use floating point values for segment duration.
     *
     * @var CmafManifestDurationFormat::*|null
     */
    private $manifestDurationFormat;

    /**
     * Minimum time of initially buffered media that is needed to ensure smooth playout.
     *
     * @var int|null
     */
    private $minBufferTime;

    /**
     * Keep this setting at the default value of 0, unless you are troubleshooting a problem with how devices play back the
     * end of your video asset. If you know that player devices are hanging on the final segment of your video because the
     * length of your final segment is too short, use this setting to specify a minimum final segment length, in seconds.
     * Choose a value that is greater than or equal to 1 and less than your segment length. When you specify a value for
     * this setting, the encoder will combine any final segment that is shorter than the length that you specify with the
     * previous segment. For example, your segment length is 3 seconds and your final segment is .5 seconds without a
     * minimum final segment length; when you set the minimum final segment length to 1, your final segment is 3.5 seconds.
     *
     * @var float|null
     */
    private $minFinalSegmentLength;

    /**
     * Specify how the value for bandwidth is determined for each video Representation in your output MPD manifest. We
     * recommend that you choose a MPD manifest bandwidth type that is compatible with your downstream player configuration.
     * Max: Use the same value that you specify for Max bitrate in the video output, in bits per second. Average: Use the
     * calculated average bitrate of the encoded video output, in bits per second.
     *
     * @var CmafMpdManifestBandwidthType::*|null
     */
    private $mpdManifestBandwidthType;

    /**
     * Specify whether your DASH profile is on-demand or main. When you choose Main profile, the service signals
     * urn:mpeg:dash:profile:isoff-main:2011 in your .mpd DASH manifest. When you choose On-demand, the service signals
     * urn:mpeg:dash:profile:isoff-on-demand:2011 in your .mpd. When you choose On-demand, you must also set the output
     * group setting Segment control to Single file.
     *
     * @var CmafMpdProfile::*|null
     */
    private $mpdProfile;

    /**
     * Use this setting only when your output video stream has B-frames, which causes the initial presentation time stamp
     * (PTS) to be offset from the initial decode time stamp (DTS). Specify how MediaConvert handles PTS when writing time
     * stamps in output DASH manifests. Choose Match initial PTS when you want MediaConvert to use the initial PTS as the
     * first time stamp in the manifest. Choose Zero-based to have MediaConvert ignore the initial PTS in the video stream
     * and instead write the initial time stamp as zero in the manifest. For outputs that don't have B-frames, the time
     * stamps in your DASH manifests start at zero regardless of your choice here.
     *
     * @var CmafPtsOffsetHandlingForBFrames::*|null
     */
    private $ptsOffsetHandlingForBframes;

    /**
     * When set to SINGLE_FILE, a single output file is generated, which is internally segmented using the Fragment Length
     * and Segment Length. When set to SEGMENTED_FILES, separate segment files will be created.
     *
     * @var CmafSegmentControl::*|null
     */
    private $segmentControl;

    /**
     * Specify the length, in whole seconds, of each segment. When you don't specify a value, MediaConvert defaults to 10.
     * Related settings: Use Segment length control to specify whether the encoder enforces this value strictly. Use Segment
     * control to specify whether MediaConvert creates separate segment files or one content file that has metadata to mark
     * the segment boundaries.
     *
     * @var int|null
     */
    private $segmentLength;

    /**
     * Specify how you want MediaConvert to determine segment lengths in this output group. To use the exact value that you
     * specify under Segment length: Choose Exact. Note that this might result in additional I-frames in the output GOP. To
     * create segment lengths that are a multiple of the GOP: Choose Multiple of GOP. MediaConvert will round up the segment
     * lengths to match the next GOP boundary. To have MediaConvert automatically determine a segment duration that is a
     * multiple of both the audio packets and the frame rates: Choose Match. When you do, also specify a target segment
     * duration under Segment length. This is useful for some ad-insertion or segment replacement workflows. Note that Match
     * has the following requirements: - Output containers: Include at least one video output and at least one audio output.
     * Audio-only outputs are not supported. - Output frame rate: Follow source is not supported. - Multiple output frame
     * rates: When you specify multiple outputs, we recommend they share a similar frame rate (as in X/3, X/2, X, or 2X).
     * For example: 5, 15, 30 and 60. Or: 25 and 50. (Outputs must share an integer multiple.) - Output audio codec: Specify
     * Advanced Audio Coding (AAC). - Output sample rate: Choose 48kHz.
     *
     * @var CmafSegmentLengthControl::*|null
     */
    private $segmentLengthControl;

    /**
     * Include or exclude RESOLUTION attribute for video in EXT-X-STREAM-INF tag of variant manifest.
     *
     * @var CmafStreamInfResolution::*|null
     */
    private $streamInfResolution;

    /**
     * When set to LEGACY, the segment target duration is always rounded up to the nearest integer value above its current
     * value in seconds. When set to SPEC\\_COMPLIANT, the segment target duration is rounded up to the nearest integer
     * value if fraction seconds are greater than or equal to 0.5 (>= 0.5) and rounded down if less than 0.5 (< 0.5). You
     * may need to use LEGACY if your client needs to ensure that the target duration is always longer than the actual
     * duration of the segment. Some older players may experience interrupted playback when the actual duration of a track
     * in a segment is longer than the target duration.
     *
     * @var CmafTargetDurationCompatibilityMode::*|null
     */
    private $targetDurationCompatibilityMode;

    /**
     * Specify the video sample composition time offset mode in the output fMP4 TRUN box. For wider player compatibility,
     * set Video composition offsets to Unsigned or leave blank. The earliest presentation time may be greater than zero,
     * and sample composition time offsets will increment using unsigned integers. For strict fMP4 video and audio timing,
     * set Video composition offsets to Signed. The earliest presentation time will be equal to zero, and sample composition
     * time offsets will increment using signed integers.
     *
     * @var CmafVideoCompositionOffsets::*|null
     */
    private $videoCompositionOffsets;

    /**
     * When set to ENABLED, a DASH MPD manifest will be generated for this output.
     *
     * @var CmafWriteDASHManifest::*|null
     */
    private $writeDashManifest;

    /**
     * When set to ENABLED, an Apple HLS manifest will be generated for this output.
     *
     * @var CmafWriteHLSManifest::*|null
     */
    private $writeHlsManifest;

    /**
     * When you enable Precise segment duration in DASH manifests, your DASH manifest shows precise segment durations. The
     * segment duration information appears inside the SegmentTimeline element, inside SegmentTemplate at the Representation
     * level. When this feature isn't enabled, the segment durations in your DASH manifest are approximate. The segment
     * duration information appears in the duration attribute of the SegmentTemplate element.
     *
     * @var CmafWriteSegmentTimelineInRepresentation::*|null
     */
    private $writeSegmentTimelineInRepresentation;

    /**
     * @param array{
     *   AdditionalManifests?: array<CmafAdditionalManifest|array>|null,
     *   BaseUrl?: string|null,
     *   ClientCache?: CmafClientCache::*|null,
     *   CodecSpecification?: CmafCodecSpecification::*|null,
     *   DashIFrameTrickPlayNameModifier?: string|null,
     *   DashManifestStyle?: DashManifestStyle::*|null,
     *   Destination?: string|null,
     *   DestinationSettings?: DestinationSettings|array|null,
     *   Encryption?: CmafEncryptionSettings|array|null,
     *   FragmentLength?: int|null,
     *   ImageBasedTrickPlay?: CmafImageBasedTrickPlay::*|null,
     *   ImageBasedTrickPlaySettings?: CmafImageBasedTrickPlaySettings|array|null,
     *   ManifestCompression?: CmafManifestCompression::*|null,
     *   ManifestDurationFormat?: CmafManifestDurationFormat::*|null,
     *   MinBufferTime?: int|null,
     *   MinFinalSegmentLength?: float|null,
     *   MpdManifestBandwidthType?: CmafMpdManifestBandwidthType::*|null,
     *   MpdProfile?: CmafMpdProfile::*|null,
     *   PtsOffsetHandlingForBFrames?: CmafPtsOffsetHandlingForBFrames::*|null,
     *   SegmentControl?: CmafSegmentControl::*|null,
     *   SegmentLength?: int|null,
     *   SegmentLengthControl?: CmafSegmentLengthControl::*|null,
     *   StreamInfResolution?: CmafStreamInfResolution::*|null,
     *   TargetDurationCompatibilityMode?: CmafTargetDurationCompatibilityMode::*|null,
     *   VideoCompositionOffsets?: CmafVideoCompositionOffsets::*|null,
     *   WriteDashManifest?: CmafWriteDASHManifest::*|null,
     *   WriteHlsManifest?: CmafWriteHLSManifest::*|null,
     *   WriteSegmentTimelineInRepresentation?: CmafWriteSegmentTimelineInRepresentation::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->additionalManifests = isset($input['AdditionalManifests']) ? array_map([CmafAdditionalManifest::class, 'create'], $input['AdditionalManifests']) : null;
        $this->baseUrl = $input['BaseUrl'] ?? null;
        $this->clientCache = $input['ClientCache'] ?? null;
        $this->codecSpecification = $input['CodecSpecification'] ?? null;
        $this->dashIframeTrickPlayNameModifier = $input['DashIFrameTrickPlayNameModifier'] ?? null;
        $this->dashManifestStyle = $input['DashManifestStyle'] ?? null;
        $this->destination = $input['Destination'] ?? null;
        $this->destinationSettings = isset($input['DestinationSettings']) ? DestinationSettings::create($input['DestinationSettings']) : null;
        $this->encryption = isset($input['Encryption']) ? CmafEncryptionSettings::create($input['Encryption']) : null;
        $this->fragmentLength = $input['FragmentLength'] ?? null;
        $this->imageBasedTrickPlay = $input['ImageBasedTrickPlay'] ?? null;
        $this->imageBasedTrickPlaySettings = isset($input['ImageBasedTrickPlaySettings']) ? CmafImageBasedTrickPlaySettings::create($input['ImageBasedTrickPlaySettings']) : null;
        $this->manifestCompression = $input['ManifestCompression'] ?? null;
        $this->manifestDurationFormat = $input['ManifestDurationFormat'] ?? null;
        $this->minBufferTime = $input['MinBufferTime'] ?? null;
        $this->minFinalSegmentLength = $input['MinFinalSegmentLength'] ?? null;
        $this->mpdManifestBandwidthType = $input['MpdManifestBandwidthType'] ?? null;
        $this->mpdProfile = $input['MpdProfile'] ?? null;
        $this->ptsOffsetHandlingForBframes = $input['PtsOffsetHandlingForBFrames'] ?? null;
        $this->segmentControl = $input['SegmentControl'] ?? null;
        $this->segmentLength = $input['SegmentLength'] ?? null;
        $this->segmentLengthControl = $input['SegmentLengthControl'] ?? null;
        $this->streamInfResolution = $input['StreamInfResolution'] ?? null;
        $this->targetDurationCompatibilityMode = $input['TargetDurationCompatibilityMode'] ?? null;
        $this->videoCompositionOffsets = $input['VideoCompositionOffsets'] ?? null;
        $this->writeDashManifest = $input['WriteDashManifest'] ?? null;
        $this->writeHlsManifest = $input['WriteHlsManifest'] ?? null;
        $this->writeSegmentTimelineInRepresentation = $input['WriteSegmentTimelineInRepresentation'] ?? null;
    }

    /**
     * @param array{
     *   AdditionalManifests?: array<CmafAdditionalManifest|array>|null,
     *   BaseUrl?: string|null,
     *   ClientCache?: CmafClientCache::*|null,
     *   CodecSpecification?: CmafCodecSpecification::*|null,
     *   DashIFrameTrickPlayNameModifier?: string|null,
     *   DashManifestStyle?: DashManifestStyle::*|null,
     *   Destination?: string|null,
     *   DestinationSettings?: DestinationSettings|array|null,
     *   Encryption?: CmafEncryptionSettings|array|null,
     *   FragmentLength?: int|null,
     *   ImageBasedTrickPlay?: CmafImageBasedTrickPlay::*|null,
     *   ImageBasedTrickPlaySettings?: CmafImageBasedTrickPlaySettings|array|null,
     *   ManifestCompression?: CmafManifestCompression::*|null,
     *   ManifestDurationFormat?: CmafManifestDurationFormat::*|null,
     *   MinBufferTime?: int|null,
     *   MinFinalSegmentLength?: float|null,
     *   MpdManifestBandwidthType?: CmafMpdManifestBandwidthType::*|null,
     *   MpdProfile?: CmafMpdProfile::*|null,
     *   PtsOffsetHandlingForBFrames?: CmafPtsOffsetHandlingForBFrames::*|null,
     *   SegmentControl?: CmafSegmentControl::*|null,
     *   SegmentLength?: int|null,
     *   SegmentLengthControl?: CmafSegmentLengthControl::*|null,
     *   StreamInfResolution?: CmafStreamInfResolution::*|null,
     *   TargetDurationCompatibilityMode?: CmafTargetDurationCompatibilityMode::*|null,
     *   VideoCompositionOffsets?: CmafVideoCompositionOffsets::*|null,
     *   WriteDashManifest?: CmafWriteDASHManifest::*|null,
     *   WriteHlsManifest?: CmafWriteHLSManifest::*|null,
     *   WriteSegmentTimelineInRepresentation?: CmafWriteSegmentTimelineInRepresentation::*|null,
     * }|CmafGroupSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return CmafAdditionalManifest[]
     */
    public function getAdditionalManifests(): array
    {
        return $this->additionalManifests ?? [];
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    /**
     * @return CmafClientCache::*|null
     */
    public function getClientCache(): ?string
    {
        return $this->clientCache;
    }

    /**
     * @return CmafCodecSpecification::*|null
     */
    public function getCodecSpecification(): ?string
    {
        return $this->codecSpecification;
    }

    public function getDashIframeTrickPlayNameModifier(): ?string
    {
        return $this->dashIframeTrickPlayNameModifier;
    }

    /**
     * @return DashManifestStyle::*|null
     */
    public function getDashManifestStyle(): ?string
    {
        return $this->dashManifestStyle;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function getDestinationSettings(): ?DestinationSettings
    {
        return $this->destinationSettings;
    }

    public function getEncryption(): ?CmafEncryptionSettings
    {
        return $this->encryption;
    }

    public function getFragmentLength(): ?int
    {
        return $this->fragmentLength;
    }

    /**
     * @return CmafImageBasedTrickPlay::*|null
     */
    public function getImageBasedTrickPlay(): ?string
    {
        return $this->imageBasedTrickPlay;
    }

    public function getImageBasedTrickPlaySettings(): ?CmafImageBasedTrickPlaySettings
    {
        return $this->imageBasedTrickPlaySettings;
    }

    /**
     * @return CmafManifestCompression::*|null
     */
    public function getManifestCompression(): ?string
    {
        return $this->manifestCompression;
    }

    /**
     * @return CmafManifestDurationFormat::*|null
     */
    public function getManifestDurationFormat(): ?string
    {
        return $this->manifestDurationFormat;
    }

    public function getMinBufferTime(): ?int
    {
        return $this->minBufferTime;
    }

    public function getMinFinalSegmentLength(): ?float
    {
        return $this->minFinalSegmentLength;
    }

    /**
     * @return CmafMpdManifestBandwidthType::*|null
     */
    public function getMpdManifestBandwidthType(): ?string
    {
        return $this->mpdManifestBandwidthType;
    }

    /**
     * @return CmafMpdProfile::*|null
     */
    public function getMpdProfile(): ?string
    {
        return $this->mpdProfile;
    }

    /**
     * @return CmafPtsOffsetHandlingForBFrames::*|null
     */
    public function getPtsOffsetHandlingForBframes(): ?string
    {
        return $this->ptsOffsetHandlingForBframes;
    }

    /**
     * @return CmafSegmentControl::*|null
     */
    public function getSegmentControl(): ?string
    {
        return $this->segmentControl;
    }

    public function getSegmentLength(): ?int
    {
        return $this->segmentLength;
    }

    /**
     * @return CmafSegmentLengthControl::*|null
     */
    public function getSegmentLengthControl(): ?string
    {
        return $this->segmentLengthControl;
    }

    /**
     * @return CmafStreamInfResolution::*|null
     */
    public function getStreamInfResolution(): ?string
    {
        return $this->streamInfResolution;
    }

    /**
     * @return CmafTargetDurationCompatibilityMode::*|null
     */
    public function getTargetDurationCompatibilityMode(): ?string
    {
        return $this->targetDurationCompatibilityMode;
    }

    /**
     * @return CmafVideoCompositionOffsets::*|null
     */
    public function getVideoCompositionOffsets(): ?string
    {
        return $this->videoCompositionOffsets;
    }

    /**
     * @return CmafWriteDASHManifest::*|null
     */
    public function getWriteDashManifest(): ?string
    {
        return $this->writeDashManifest;
    }

    /**
     * @return CmafWriteHLSManifest::*|null
     */
    public function getWriteHlsManifest(): ?string
    {
        return $this->writeHlsManifest;
    }

    /**
     * @return CmafWriteSegmentTimelineInRepresentation::*|null
     */
    public function getWriteSegmentTimelineInRepresentation(): ?string
    {
        return $this->writeSegmentTimelineInRepresentation;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->additionalManifests) {
            $index = -1;
            $payload['additionalManifests'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['additionalManifests'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->baseUrl) {
            $payload['baseUrl'] = $v;
        }
        if (null !== $v = $this->clientCache) {
            if (!CmafClientCache::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "clientCache" for "%s". The value "%s" is not a valid "CmafClientCache".', __CLASS__, $v));
            }
            $payload['clientCache'] = $v;
        }
        if (null !== $v = $this->codecSpecification) {
            if (!CmafCodecSpecification::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "codecSpecification" for "%s". The value "%s" is not a valid "CmafCodecSpecification".', __CLASS__, $v));
            }
            $payload['codecSpecification'] = $v;
        }
        if (null !== $v = $this->dashIframeTrickPlayNameModifier) {
            $payload['dashIFrameTrickPlayNameModifier'] = $v;
        }
        if (null !== $v = $this->dashManifestStyle) {
            if (!DashManifestStyle::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "dashManifestStyle" for "%s". The value "%s" is not a valid "DashManifestStyle".', __CLASS__, $v));
            }
            $payload['dashManifestStyle'] = $v;
        }
        if (null !== $v = $this->destination) {
            $payload['destination'] = $v;
        }
        if (null !== $v = $this->destinationSettings) {
            $payload['destinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->encryption) {
            $payload['encryption'] = $v->requestBody();
        }
        if (null !== $v = $this->fragmentLength) {
            $payload['fragmentLength'] = $v;
        }
        if (null !== $v = $this->imageBasedTrickPlay) {
            if (!CmafImageBasedTrickPlay::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "imageBasedTrickPlay" for "%s". The value "%s" is not a valid "CmafImageBasedTrickPlay".', __CLASS__, $v));
            }
            $payload['imageBasedTrickPlay'] = $v;
        }
        if (null !== $v = $this->imageBasedTrickPlaySettings) {
            $payload['imageBasedTrickPlaySettings'] = $v->requestBody();
        }
        if (null !== $v = $this->manifestCompression) {
            if (!CmafManifestCompression::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "manifestCompression" for "%s". The value "%s" is not a valid "CmafManifestCompression".', __CLASS__, $v));
            }
            $payload['manifestCompression'] = $v;
        }
        if (null !== $v = $this->manifestDurationFormat) {
            if (!CmafManifestDurationFormat::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "manifestDurationFormat" for "%s". The value "%s" is not a valid "CmafManifestDurationFormat".', __CLASS__, $v));
            }
            $payload['manifestDurationFormat'] = $v;
        }
        if (null !== $v = $this->minBufferTime) {
            $payload['minBufferTime'] = $v;
        }
        if (null !== $v = $this->minFinalSegmentLength) {
            $payload['minFinalSegmentLength'] = $v;
        }
        if (null !== $v = $this->mpdManifestBandwidthType) {
            if (!CmafMpdManifestBandwidthType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "mpdManifestBandwidthType" for "%s". The value "%s" is not a valid "CmafMpdManifestBandwidthType".', __CLASS__, $v));
            }
            $payload['mpdManifestBandwidthType'] = $v;
        }
        if (null !== $v = $this->mpdProfile) {
            if (!CmafMpdProfile::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "mpdProfile" for "%s". The value "%s" is not a valid "CmafMpdProfile".', __CLASS__, $v));
            }
            $payload['mpdProfile'] = $v;
        }
        if (null !== $v = $this->ptsOffsetHandlingForBframes) {
            if (!CmafPtsOffsetHandlingForBFrames::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ptsOffsetHandlingForBFrames" for "%s". The value "%s" is not a valid "CmafPtsOffsetHandlingForBFrames".', __CLASS__, $v));
            }
            $payload['ptsOffsetHandlingForBFrames'] = $v;
        }
        if (null !== $v = $this->segmentControl) {
            if (!CmafSegmentControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "segmentControl" for "%s". The value "%s" is not a valid "CmafSegmentControl".', __CLASS__, $v));
            }
            $payload['segmentControl'] = $v;
        }
        if (null !== $v = $this->segmentLength) {
            $payload['segmentLength'] = $v;
        }
        if (null !== $v = $this->segmentLengthControl) {
            if (!CmafSegmentLengthControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "segmentLengthControl" for "%s". The value "%s" is not a valid "CmafSegmentLengthControl".', __CLASS__, $v));
            }
            $payload['segmentLengthControl'] = $v;
        }
        if (null !== $v = $this->streamInfResolution) {
            if (!CmafStreamInfResolution::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "streamInfResolution" for "%s". The value "%s" is not a valid "CmafStreamInfResolution".', __CLASS__, $v));
            }
            $payload['streamInfResolution'] = $v;
        }
        if (null !== $v = $this->targetDurationCompatibilityMode) {
            if (!CmafTargetDurationCompatibilityMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "targetDurationCompatibilityMode" for "%s". The value "%s" is not a valid "CmafTargetDurationCompatibilityMode".', __CLASS__, $v));
            }
            $payload['targetDurationCompatibilityMode'] = $v;
        }
        if (null !== $v = $this->videoCompositionOffsets) {
            if (!CmafVideoCompositionOffsets::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "videoCompositionOffsets" for "%s". The value "%s" is not a valid "CmafVideoCompositionOffsets".', __CLASS__, $v));
            }
            $payload['videoCompositionOffsets'] = $v;
        }
        if (null !== $v = $this->writeDashManifest) {
            if (!CmafWriteDASHManifest::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "writeDashManifest" for "%s". The value "%s" is not a valid "CmafWriteDASHManifest".', __CLASS__, $v));
            }
            $payload['writeDashManifest'] = $v;
        }
        if (null !== $v = $this->writeHlsManifest) {
            if (!CmafWriteHLSManifest::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "writeHlsManifest" for "%s". The value "%s" is not a valid "CmafWriteHLSManifest".', __CLASS__, $v));
            }
            $payload['writeHlsManifest'] = $v;
        }
        if (null !== $v = $this->writeSegmentTimelineInRepresentation) {
            if (!CmafWriteSegmentTimelineInRepresentation::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "writeSegmentTimelineInRepresentation" for "%s". The value "%s" is not a valid "CmafWriteSegmentTimelineInRepresentation".', __CLASS__, $v));
            }
            $payload['writeSegmentTimelineInRepresentation'] = $v;
        }

        return $payload;
    }
}
