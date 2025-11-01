<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\DashIsoGroupAudioChannelConfigSchemeIdUri;
use AsyncAws\MediaConvert\Enum\DashIsoHbbtvCompliance;
use AsyncAws\MediaConvert\Enum\DashIsoImageBasedTrickPlay;
use AsyncAws\MediaConvert\Enum\DashIsoMpdManifestBandwidthType;
use AsyncAws\MediaConvert\Enum\DashIsoMpdProfile;
use AsyncAws\MediaConvert\Enum\DashIsoPtsOffsetHandlingForBFrames;
use AsyncAws\MediaConvert\Enum\DashIsoSegmentControl;
use AsyncAws\MediaConvert\Enum\DashIsoSegmentLengthControl;
use AsyncAws\MediaConvert\Enum\DashIsoVideoCompositionOffsets;
use AsyncAws\MediaConvert\Enum\DashIsoWriteSegmentTimelineInRepresentation;
use AsyncAws\MediaConvert\Enum\DashManifestStyle;

/**
 * Settings related to your DASH output package. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/outputs-file-ABR.html.
 */
final class DashIsoGroupSettings
{
    /**
     * By default, the service creates one .mpd DASH manifest for each DASH ISO output group in your job. This default
     * manifest references every output in the output group. To create additional DASH manifests that reference a subset of
     * the outputs in the output group, specify a list of them here.
     *
     * @var DashAdditionalManifest[]|null
     */
    private $additionalManifests;

    /**
     * Use this setting only when your audio codec is a Dolby one (AC3, EAC3, or Atmos) and your downstream workflow
     * requires that your DASH manifest use the Dolby channel configuration tag, rather than the MPEG one. For example, you
     * might need to use this to make dynamic ad insertion work. Specify which audio channel configuration scheme ID URI
     * MediaConvert writes in your DASH manifest. Keep the default value, MPEG channel configuration, to have MediaConvert
     * write this: urn:mpeg:mpegB:cicp:ChannelConfiguration. Choose Dolby channel configuration to have MediaConvert write
     * this instead: tag:dolby.com,2014:dash:audio_channel_configuration:2011.
     *
     * @var DashIsoGroupAudioChannelConfigSchemeIdUri::*|null
     */
    private $audioChannelConfigSchemeIdUri;

    /**
     * A partial URI prefix that will be put in the manifest (.mpd) file at the top level BaseURL element. Can be used if
     * streams are delivered from a different URL than the manifest file.
     *
     * @var string|null
     */
    private $baseUrl;

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
     * Specify how MediaConvert writes SegmentTimeline in your output DASH manifest. To write a SegmentTimeline for outputs
     * that you also specify a Name modifier for: Keep the default value, Basic. Note that if you do not specify a name
     * modifier for an output, MediaConvert will not write a SegmentTimeline for it. To write a common SegmentTimeline in
     * the video AdaptationSet: Choose Compact. Note that MediaConvert will still write a SegmentTimeline in any
     * Representation that does not share a common timeline. To write a video AdaptationSet for each different output
     * framerate, and a common SegmentTimeline in each AdaptationSet: Choose Distinct. To write a SegmentTimeline in each
     * AdaptationSet: Choose Full.
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
     * @var DashIsoEncryptionSettings|null
     */
    private $encryption;

    /**
     * Length of fragments to generate (in seconds). Fragment length must be compatible with GOP size and Framerate. Note
     * that fragments will end on the next keyframe after this number of seconds, so actual fragment length may be longer.
     * When Emit Single File is checked, the fragmentation is internal to a single output file and it does not cause the
     * creation of many output files as in other output types.
     *
     * @var int|null
     */
    private $fragmentLength;

    /**
     * Supports HbbTV specification as indicated.
     *
     * @var DashIsoHbbtvCompliance::*|null
     */
    private $hbbtvCompliance;

    /**
     * Specify whether MediaConvert generates images for trick play. Keep the default value, None, to not generate any
     * images. Choose Thumbnail to generate tiled thumbnails. Choose Thumbnail and full frame to generate tiled thumbnails
     * and full-resolution images of single frames. MediaConvert adds an entry in the .mpd manifest for each set of images
     * that you generate. A common application for these images is Roku trick mode. The thumbnails and full-frame images
     * that MediaConvert creates with this feature are compatible with this Roku specification:
     * https://developer.roku.com/docs/developer-program/media-playback/trick-mode/hls-and-dash.md.
     *
     * @var DashIsoImageBasedTrickPlay::*|null
     */
    private $imageBasedTrickPlay;

    /**
     * Tile and thumbnail settings applicable when imageBasedTrickPlay is ADVANCED.
     *
     * @var DashIsoImageBasedTrickPlaySettings|null
     */
    private $imageBasedTrickPlaySettings;

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
     * @var DashIsoMpdManifestBandwidthType::*|null
     */
    private $mpdManifestBandwidthType;

    /**
     * Specify whether your DASH profile is on-demand or main. When you choose Main profile, the service signals
     * urn:mpeg:dash:profile:isoff-main:2011 in your .mpd DASH manifest. When you choose On-demand, the service signals
     * urn:mpeg:dash:profile:isoff-on-demand:2011 in your .mpd. When you choose On-demand, you must also set the output
     * group setting Segment control to Single file.
     *
     * @var DashIsoMpdProfile::*|null
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
     * @var DashIsoPtsOffsetHandlingForBFrames::*|null
     */
    private $ptsOffsetHandlingForBframes;

    /**
     * When set to SINGLE_FILE, a single output file is generated, which is internally segmented using the Fragment Length
     * and Segment Length. When set to SEGMENTED_FILES, separate segment files will be created.
     *
     * @var DashIsoSegmentControl::*|null
     */
    private $segmentControl;

    /**
     * Specify the length, in whole seconds, of each segment. When you don't specify a value, MediaConvert defaults to 30.
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
     * @var DashIsoSegmentLengthControl::*|null
     */
    private $segmentLengthControl;

    /**
     * Specify the video sample composition time offset mode in the output fMP4 TRUN box. For wider player compatibility,
     * set Video composition offsets to Unsigned or leave blank. The earliest presentation time may be greater than zero,
     * and sample composition time offsets will increment using unsigned integers. For strict fMP4 video and audio timing,
     * set Video composition offsets to Signed. The earliest presentation time will be equal to zero, and sample composition
     * time offsets will increment using signed integers.
     *
     * @var DashIsoVideoCompositionOffsets::*|null
     */
    private $videoCompositionOffsets;

    /**
     * If you get an HTTP error in the 400 range when you play back your DASH output, enable this setting and run your
     * transcoding job again. When you enable this setting, the service writes precise segment durations in the DASH
     * manifest. The segment duration information appears inside the SegmentTimeline element, inside SegmentTemplate at the
     * Representation level. When you don't enable this setting, the service writes approximate segment durations in your
     * DASH manifest.
     *
     * @var DashIsoWriteSegmentTimelineInRepresentation::*|null
     */
    private $writeSegmentTimelineInRepresentation;

    /**
     * @param array{
     *   AdditionalManifests?: array<DashAdditionalManifest|array>|null,
     *   AudioChannelConfigSchemeIdUri?: DashIsoGroupAudioChannelConfigSchemeIdUri::*|null,
     *   BaseUrl?: string|null,
     *   DashIFrameTrickPlayNameModifier?: string|null,
     *   DashManifestStyle?: DashManifestStyle::*|null,
     *   Destination?: string|null,
     *   DestinationSettings?: DestinationSettings|array|null,
     *   Encryption?: DashIsoEncryptionSettings|array|null,
     *   FragmentLength?: int|null,
     *   HbbtvCompliance?: DashIsoHbbtvCompliance::*|null,
     *   ImageBasedTrickPlay?: DashIsoImageBasedTrickPlay::*|null,
     *   ImageBasedTrickPlaySettings?: DashIsoImageBasedTrickPlaySettings|array|null,
     *   MinBufferTime?: int|null,
     *   MinFinalSegmentLength?: float|null,
     *   MpdManifestBandwidthType?: DashIsoMpdManifestBandwidthType::*|null,
     *   MpdProfile?: DashIsoMpdProfile::*|null,
     *   PtsOffsetHandlingForBFrames?: DashIsoPtsOffsetHandlingForBFrames::*|null,
     *   SegmentControl?: DashIsoSegmentControl::*|null,
     *   SegmentLength?: int|null,
     *   SegmentLengthControl?: DashIsoSegmentLengthControl::*|null,
     *   VideoCompositionOffsets?: DashIsoVideoCompositionOffsets::*|null,
     *   WriteSegmentTimelineInRepresentation?: DashIsoWriteSegmentTimelineInRepresentation::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->additionalManifests = isset($input['AdditionalManifests']) ? array_map([DashAdditionalManifest::class, 'create'], $input['AdditionalManifests']) : null;
        $this->audioChannelConfigSchemeIdUri = $input['AudioChannelConfigSchemeIdUri'] ?? null;
        $this->baseUrl = $input['BaseUrl'] ?? null;
        $this->dashIframeTrickPlayNameModifier = $input['DashIFrameTrickPlayNameModifier'] ?? null;
        $this->dashManifestStyle = $input['DashManifestStyle'] ?? null;
        $this->destination = $input['Destination'] ?? null;
        $this->destinationSettings = isset($input['DestinationSettings']) ? DestinationSettings::create($input['DestinationSettings']) : null;
        $this->encryption = isset($input['Encryption']) ? DashIsoEncryptionSettings::create($input['Encryption']) : null;
        $this->fragmentLength = $input['FragmentLength'] ?? null;
        $this->hbbtvCompliance = $input['HbbtvCompliance'] ?? null;
        $this->imageBasedTrickPlay = $input['ImageBasedTrickPlay'] ?? null;
        $this->imageBasedTrickPlaySettings = isset($input['ImageBasedTrickPlaySettings']) ? DashIsoImageBasedTrickPlaySettings::create($input['ImageBasedTrickPlaySettings']) : null;
        $this->minBufferTime = $input['MinBufferTime'] ?? null;
        $this->minFinalSegmentLength = $input['MinFinalSegmentLength'] ?? null;
        $this->mpdManifestBandwidthType = $input['MpdManifestBandwidthType'] ?? null;
        $this->mpdProfile = $input['MpdProfile'] ?? null;
        $this->ptsOffsetHandlingForBframes = $input['PtsOffsetHandlingForBFrames'] ?? null;
        $this->segmentControl = $input['SegmentControl'] ?? null;
        $this->segmentLength = $input['SegmentLength'] ?? null;
        $this->segmentLengthControl = $input['SegmentLengthControl'] ?? null;
        $this->videoCompositionOffsets = $input['VideoCompositionOffsets'] ?? null;
        $this->writeSegmentTimelineInRepresentation = $input['WriteSegmentTimelineInRepresentation'] ?? null;
    }

    /**
     * @param array{
     *   AdditionalManifests?: array<DashAdditionalManifest|array>|null,
     *   AudioChannelConfigSchemeIdUri?: DashIsoGroupAudioChannelConfigSchemeIdUri::*|null,
     *   BaseUrl?: string|null,
     *   DashIFrameTrickPlayNameModifier?: string|null,
     *   DashManifestStyle?: DashManifestStyle::*|null,
     *   Destination?: string|null,
     *   DestinationSettings?: DestinationSettings|array|null,
     *   Encryption?: DashIsoEncryptionSettings|array|null,
     *   FragmentLength?: int|null,
     *   HbbtvCompliance?: DashIsoHbbtvCompliance::*|null,
     *   ImageBasedTrickPlay?: DashIsoImageBasedTrickPlay::*|null,
     *   ImageBasedTrickPlaySettings?: DashIsoImageBasedTrickPlaySettings|array|null,
     *   MinBufferTime?: int|null,
     *   MinFinalSegmentLength?: float|null,
     *   MpdManifestBandwidthType?: DashIsoMpdManifestBandwidthType::*|null,
     *   MpdProfile?: DashIsoMpdProfile::*|null,
     *   PtsOffsetHandlingForBFrames?: DashIsoPtsOffsetHandlingForBFrames::*|null,
     *   SegmentControl?: DashIsoSegmentControl::*|null,
     *   SegmentLength?: int|null,
     *   SegmentLengthControl?: DashIsoSegmentLengthControl::*|null,
     *   VideoCompositionOffsets?: DashIsoVideoCompositionOffsets::*|null,
     *   WriteSegmentTimelineInRepresentation?: DashIsoWriteSegmentTimelineInRepresentation::*|null,
     * }|DashIsoGroupSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DashAdditionalManifest[]
     */
    public function getAdditionalManifests(): array
    {
        return $this->additionalManifests ?? [];
    }

    /**
     * @return DashIsoGroupAudioChannelConfigSchemeIdUri::*|null
     */
    public function getAudioChannelConfigSchemeIdUri(): ?string
    {
        return $this->audioChannelConfigSchemeIdUri;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
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

    public function getEncryption(): ?DashIsoEncryptionSettings
    {
        return $this->encryption;
    }

    public function getFragmentLength(): ?int
    {
        return $this->fragmentLength;
    }

    /**
     * @return DashIsoHbbtvCompliance::*|null
     */
    public function getHbbtvCompliance(): ?string
    {
        return $this->hbbtvCompliance;
    }

    /**
     * @return DashIsoImageBasedTrickPlay::*|null
     */
    public function getImageBasedTrickPlay(): ?string
    {
        return $this->imageBasedTrickPlay;
    }

    public function getImageBasedTrickPlaySettings(): ?DashIsoImageBasedTrickPlaySettings
    {
        return $this->imageBasedTrickPlaySettings;
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
     * @return DashIsoMpdManifestBandwidthType::*|null
     */
    public function getMpdManifestBandwidthType(): ?string
    {
        return $this->mpdManifestBandwidthType;
    }

    /**
     * @return DashIsoMpdProfile::*|null
     */
    public function getMpdProfile(): ?string
    {
        return $this->mpdProfile;
    }

    /**
     * @return DashIsoPtsOffsetHandlingForBFrames::*|null
     */
    public function getPtsOffsetHandlingForBframes(): ?string
    {
        return $this->ptsOffsetHandlingForBframes;
    }

    /**
     * @return DashIsoSegmentControl::*|null
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
     * @return DashIsoSegmentLengthControl::*|null
     */
    public function getSegmentLengthControl(): ?string
    {
        return $this->segmentLengthControl;
    }

    /**
     * @return DashIsoVideoCompositionOffsets::*|null
     */
    public function getVideoCompositionOffsets(): ?string
    {
        return $this->videoCompositionOffsets;
    }

    /**
     * @return DashIsoWriteSegmentTimelineInRepresentation::*|null
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
        if (null !== $v = $this->audioChannelConfigSchemeIdUri) {
            if (!DashIsoGroupAudioChannelConfigSchemeIdUri::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioChannelConfigSchemeIdUri" for "%s". The value "%s" is not a valid "DashIsoGroupAudioChannelConfigSchemeIdUri".', __CLASS__, $v));
            }
            $payload['audioChannelConfigSchemeIdUri'] = $v;
        }
        if (null !== $v = $this->baseUrl) {
            $payload['baseUrl'] = $v;
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
        if (null !== $v = $this->hbbtvCompliance) {
            if (!DashIsoHbbtvCompliance::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "hbbtvCompliance" for "%s". The value "%s" is not a valid "DashIsoHbbtvCompliance".', __CLASS__, $v));
            }
            $payload['hbbtvCompliance'] = $v;
        }
        if (null !== $v = $this->imageBasedTrickPlay) {
            if (!DashIsoImageBasedTrickPlay::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "imageBasedTrickPlay" for "%s". The value "%s" is not a valid "DashIsoImageBasedTrickPlay".', __CLASS__, $v));
            }
            $payload['imageBasedTrickPlay'] = $v;
        }
        if (null !== $v = $this->imageBasedTrickPlaySettings) {
            $payload['imageBasedTrickPlaySettings'] = $v->requestBody();
        }
        if (null !== $v = $this->minBufferTime) {
            $payload['minBufferTime'] = $v;
        }
        if (null !== $v = $this->minFinalSegmentLength) {
            $payload['minFinalSegmentLength'] = $v;
        }
        if (null !== $v = $this->mpdManifestBandwidthType) {
            if (!DashIsoMpdManifestBandwidthType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "mpdManifestBandwidthType" for "%s". The value "%s" is not a valid "DashIsoMpdManifestBandwidthType".', __CLASS__, $v));
            }
            $payload['mpdManifestBandwidthType'] = $v;
        }
        if (null !== $v = $this->mpdProfile) {
            if (!DashIsoMpdProfile::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "mpdProfile" for "%s". The value "%s" is not a valid "DashIsoMpdProfile".', __CLASS__, $v));
            }
            $payload['mpdProfile'] = $v;
        }
        if (null !== $v = $this->ptsOffsetHandlingForBframes) {
            if (!DashIsoPtsOffsetHandlingForBFrames::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ptsOffsetHandlingForBFrames" for "%s". The value "%s" is not a valid "DashIsoPtsOffsetHandlingForBFrames".', __CLASS__, $v));
            }
            $payload['ptsOffsetHandlingForBFrames'] = $v;
        }
        if (null !== $v = $this->segmentControl) {
            if (!DashIsoSegmentControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "segmentControl" for "%s". The value "%s" is not a valid "DashIsoSegmentControl".', __CLASS__, $v));
            }
            $payload['segmentControl'] = $v;
        }
        if (null !== $v = $this->segmentLength) {
            $payload['segmentLength'] = $v;
        }
        if (null !== $v = $this->segmentLengthControl) {
            if (!DashIsoSegmentLengthControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "segmentLengthControl" for "%s". The value "%s" is not a valid "DashIsoSegmentLengthControl".', __CLASS__, $v));
            }
            $payload['segmentLengthControl'] = $v;
        }
        if (null !== $v = $this->videoCompositionOffsets) {
            if (!DashIsoVideoCompositionOffsets::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "videoCompositionOffsets" for "%s". The value "%s" is not a valid "DashIsoVideoCompositionOffsets".', __CLASS__, $v));
            }
            $payload['videoCompositionOffsets'] = $v;
        }
        if (null !== $v = $this->writeSegmentTimelineInRepresentation) {
            if (!DashIsoWriteSegmentTimelineInRepresentation::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "writeSegmentTimelineInRepresentation" for "%s". The value "%s" is not a valid "DashIsoWriteSegmentTimelineInRepresentation".', __CLASS__, $v));
            }
            $payload['writeSegmentTimelineInRepresentation'] = $v;
        }

        return $payload;
    }
}
