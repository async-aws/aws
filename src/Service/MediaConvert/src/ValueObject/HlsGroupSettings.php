<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\HlsAdMarkers;
use AsyncAws\MediaConvert\Enum\HlsAudioOnlyHeader;
use AsyncAws\MediaConvert\Enum\HlsCaptionLanguageSetting;
use AsyncAws\MediaConvert\Enum\HlsCaptionSegmentLengthControl;
use AsyncAws\MediaConvert\Enum\HlsClientCache;
use AsyncAws\MediaConvert\Enum\HlsCodecSpecification;
use AsyncAws\MediaConvert\Enum\HlsDirectoryStructure;
use AsyncAws\MediaConvert\Enum\HlsImageBasedTrickPlay;
use AsyncAws\MediaConvert\Enum\HlsManifestCompression;
use AsyncAws\MediaConvert\Enum\HlsManifestDurationFormat;
use AsyncAws\MediaConvert\Enum\HlsOutputSelection;
use AsyncAws\MediaConvert\Enum\HlsProgramDateTime;
use AsyncAws\MediaConvert\Enum\HlsSegmentControl;
use AsyncAws\MediaConvert\Enum\HlsSegmentLengthControl;
use AsyncAws\MediaConvert\Enum\HlsStreamInfResolution;
use AsyncAws\MediaConvert\Enum\HlsTargetDurationCompatibilityMode;
use AsyncAws\MediaConvert\Enum\HlsTimedMetadataId3Frame;

/**
 * Settings related to your HLS output package. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/outputs-file-ABR.html. When you work directly in your JSON job
 * specification, include this object and any required children when you set Type, under OutputGroupSettings, to
 * HLS_GROUP_SETTINGS.
 */
final class HlsGroupSettings
{
    /**
     * Choose one or more ad marker types to decorate your Apple HLS manifest. This setting does not determine whether
     * SCTE-35 markers appear in the outputs themselves.
     */
    private $adMarkers;

    /**
     * By default, the service creates one top-level .m3u8 HLS manifest for each HLS output group in your job. This default
     * manifest references every output in the output group. To create additional top-level manifests that reference a
     * subset of the outputs in the output group, specify a list of them here.
     */
    private $additionalManifests;

    /**
     * Ignore this setting unless you are using FairPlay DRM with Verimatrix and you encounter playback issues. Keep the
     * default value, Include (INCLUDE), to output audio-only headers. Choose Exclude (EXCLUDE) to remove the audio-only
     * headers from your audio segments.
     */
    private $audioOnlyHeader;

    /**
     * A partial URI prefix that will be prepended to each output in the media .m3u8 file. Can be used if base manifest is
     * delivered from a different URL than the main .m3u8 file.
     */
    private $baseUrl;

    /**
     * Language to be used on Caption outputs.
     */
    private $captionLanguageMappings;

    /**
     * Applies only to 608 Embedded output captions. Insert: Include CLOSED-CAPTIONS lines in the manifest. Specify at least
     * one language in the CC1 Language Code field. One CLOSED-CAPTION line is added for each Language Code you specify.
     * Make sure to specify the languages in the order in which they appear in the original source (if the source is
     * embedded format) or the order of the caption selectors (if the source is other than embedded). Otherwise, languages
     * in the manifest will not match up properly with the output captions. None: Include CLOSED-CAPTIONS=NONE line in the
     * manifest. Omit: Omit any CLOSED-CAPTIONS line from the manifest.
     */
    private $captionLanguageSetting;

    /**
     * Set Caption segment length control (CaptionSegmentLengthControl) to Match video (MATCH_VIDEO) to create caption
     * segments that align with the video segments from the first video output in this output group. For example, if the
     * video segments are 2 seconds long, your WebVTT segments will also be 2 seconds long. Keep the default setting, Large
     * segments (LARGE_SEGMENTS) to create caption segments that are 300 seconds long.
     */
    private $captionSegmentLengthControl;

    /**
     * Disable this setting only when your workflow requires the #EXT-X-ALLOW-CACHE:no tag. Otherwise, keep the default
     * value Enabled (ENABLED) and control caching in your video distribution set up. For example, use the Cache-Control
     * http header.
     */
    private $clientCache;

    /**
     * Specification to use (RFC-6381 or the default RFC-4281) during m3u8 playlist generation.
     */
    private $codecSpecification;

    /**
     * Use Destination (Destination) to specify the S3 output location and the output filename base. Destination accepts
     * format identifiers. If you do not specify the base filename in the URI, the service will use the filename of the
     * input file. If your job has multiple inputs, the service uses the filename of the first input file.
     */
    private $destination;

    /**
     * Settings associated with the destination. Will vary based on the type of destination.
     */
    private $destinationSettings;

    /**
     * Indicates whether segments should be placed in subdirectories.
     */
    private $directoryStructure;

    /**
     * DRM settings.
     */
    private $encryption;

    /**
     * Specify whether MediaConvert generates images for trick play. Keep the default value, None (NONE), to not generate
     * any images. Choose Thumbnail (THUMBNAIL) to generate tiled thumbnails. Choose Thumbnail and full frame
     * (THUMBNAIL_AND_FULLFRAME) to generate tiled thumbnails and full-resolution images of single frames. MediaConvert
     * creates a child manifest for each set of images that you generate and adds corresponding entries to the parent
     * manifest. A common application for these images is Roku trick mode. The thumbnails and full-frame images that
     * MediaConvert creates with this feature are compatible with this Roku specification:
     * https://developer.roku.com/docs/developer-program/media-playback/trick-mode/hls-and-dash.md.
     */
    private $imageBasedTrickPlay;

    /**
     * Tile and thumbnail settings applicable when imageBasedTrickPlay is ADVANCED.
     */
    private $imageBasedTrickPlaySettings;

    /**
     * When set to GZIP, compresses HLS playlist.
     */
    private $manifestCompression;

    /**
     * Indicates whether the output manifest should use floating point values for segment duration.
     */
    private $manifestDurationFormat;

    /**
     * Keep this setting at the default value of 0, unless you are troubleshooting a problem with how devices play back the
     * end of your video asset. If you know that player devices are hanging on the final segment of your video because the
     * length of your final segment is too short, use this setting to specify a minimum final segment length, in seconds.
     * Choose a value that is greater than or equal to 1 and less than your segment length. When you specify a value for
     * this setting, the encoder will combine any final segment that is shorter than the length that you specify with the
     * previous segment. For example, your segment length is 3 seconds and your final segment is .5 seconds without a
     * minimum final segment length; when you set the minimum final segment length to 1, your final segment is 3.5 seconds.
     */
    private $minFinalSegmentLength;

    /**
     * When set, Minimum Segment Size is enforced by looking ahead and back within the specified range for a nearby avail
     * and extending the segment size if needed.
     */
    private $minSegmentLength;

    /**
     * Indicates whether the .m3u8 manifest file should be generated for this HLS output group.
     */
    private $outputSelection;

    /**
     * Includes or excludes EXT-X-PROGRAM-DATE-TIME tag in .m3u8 manifest files. The value is calculated as follows: either
     * the program date and time are initialized using the input timecode source, or the time is initialized using the input
     * timecode source and the date is initialized using the timestamp_offset.
     */
    private $programDateTime;

    /**
     * Period of insertion of EXT-X-PROGRAM-DATE-TIME entry, in seconds.
     */
    private $programDateTimePeriod;

    /**
     * When set to SINGLE_FILE, emits program as a single media resource (.ts) file, uses #EXT-X-BYTERANGE tags to index
     * segment for playback.
     */
    private $segmentControl;

    /**
     * Specify the length, in whole seconds, of each segment. When you don't specify a value, MediaConvert defaults to 10.
     * Related settings: Use Segment length control (SegmentLengthControl) to specify whether the encoder enforces this
     * value strictly. Use Segment control (HlsSegmentControl) to specify whether MediaConvert creates separate segment
     * files or one content file that has metadata to mark the segment boundaries.
     */
    private $segmentLength;

    /**
     * Specify how you want MediaConvert to determine the segment length. Choose Exact (EXACT) to have the encoder use the
     * exact length that you specify with the setting Segment length (SegmentLength). This might result in extra I-frames.
     * Choose Multiple of GOP (GOP_MULTIPLE) to have the encoder round up the segment lengths to match the next GOP
     * boundary.
     */
    private $segmentLengthControl;

    /**
     * Specify the number of segments to write to a subdirectory before starting a new one. You  must also set Directory
     * structure to Subdirectory per stream for this setting to have an effect.
     */
    private $segmentsPerSubdirectory;

    /**
     * Include or exclude RESOLUTION attribute for video in EXT-X-STREAM-INF tag of variant manifest.
     */
    private $streamInfResolution;

    /**
     * When set to LEGACY, the segment target duration is always rounded up to the nearest integer value above its current
     * value in seconds. When set to SPEC\\_COMPLIANT, the segment target duration is rounded up to the nearest integer
     * value if fraction seconds are greater than or equal to 0.5 (>= 0.5) and rounded down if less than 0.5 (< 0.5). You
     * may need to use LEGACY if your client needs to ensure that the target duration is always longer than the actual
     * duration of the segment. Some older players may experience interrupted playback when the actual duration of a track
     * in a segment is longer than the target duration.
     */
    private $targetDurationCompatibilityMode;

    /**
     * Specify the type of the ID3 frame (timedMetadataId3Frame) to use for ID3 timestamps (timedMetadataId3Period) in your
     * output. To include ID3 timestamps: Specify PRIV (PRIV) or TDRL (TDRL) and set ID3 metadata (timedMetadata) to
     * Passthrough (PASSTHROUGH). To exclude ID3 timestamps: Set ID3 timestamp frame type to None (NONE).
     */
    private $timedMetadataId3Frame;

    /**
     * Specify the interval in seconds to write ID3 timestamps in your output. The first timestamp starts at the output
     * timecode and date, and increases incrementally with each ID3 timestamp. To use the default interval of 10 seconds:
     * Leave blank. To include this metadata in your output: Set ID3 timestamp frame type (timedMetadataId3Frame) to PRIV
     * (PRIV) or TDRL (TDRL), and set ID3 metadata (timedMetadata) to Passthrough (PASSTHROUGH).
     */
    private $timedMetadataId3Period;

    /**
     * Provides an extra millisecond delta offset to fine tune the timestamps.
     */
    private $timestampDeltaMilliseconds;

    /**
     * @param array{
     *   AdMarkers?: null|list<HlsAdMarkers::*>,
     *   AdditionalManifests?: null|HlsAdditionalManifest[],
     *   AudioOnlyHeader?: null|HlsAudioOnlyHeader::*,
     *   BaseUrl?: null|string,
     *   CaptionLanguageMappings?: null|HlsCaptionLanguageMapping[],
     *   CaptionLanguageSetting?: null|HlsCaptionLanguageSetting::*,
     *   CaptionSegmentLengthControl?: null|HlsCaptionSegmentLengthControl::*,
     *   ClientCache?: null|HlsClientCache::*,
     *   CodecSpecification?: null|HlsCodecSpecification::*,
     *   Destination?: null|string,
     *   DestinationSettings?: null|DestinationSettings|array,
     *   DirectoryStructure?: null|HlsDirectoryStructure::*,
     *   Encryption?: null|HlsEncryptionSettings|array,
     *   ImageBasedTrickPlay?: null|HlsImageBasedTrickPlay::*,
     *   ImageBasedTrickPlaySettings?: null|HlsImageBasedTrickPlaySettings|array,
     *   ManifestCompression?: null|HlsManifestCompression::*,
     *   ManifestDurationFormat?: null|HlsManifestDurationFormat::*,
     *   MinFinalSegmentLength?: null|float,
     *   MinSegmentLength?: null|int,
     *   OutputSelection?: null|HlsOutputSelection::*,
     *   ProgramDateTime?: null|HlsProgramDateTime::*,
     *   ProgramDateTimePeriod?: null|int,
     *   SegmentControl?: null|HlsSegmentControl::*,
     *   SegmentLength?: null|int,
     *   SegmentLengthControl?: null|HlsSegmentLengthControl::*,
     *   SegmentsPerSubdirectory?: null|int,
     *   StreamInfResolution?: null|HlsStreamInfResolution::*,
     *   TargetDurationCompatibilityMode?: null|HlsTargetDurationCompatibilityMode::*,
     *   TimedMetadataId3Frame?: null|HlsTimedMetadataId3Frame::*,
     *   TimedMetadataId3Period?: null|int,
     *   TimestampDeltaMilliseconds?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->adMarkers = $input['AdMarkers'] ?? null;
        $this->additionalManifests = isset($input['AdditionalManifests']) ? array_map([HlsAdditionalManifest::class, 'create'], $input['AdditionalManifests']) : null;
        $this->audioOnlyHeader = $input['AudioOnlyHeader'] ?? null;
        $this->baseUrl = $input['BaseUrl'] ?? null;
        $this->captionLanguageMappings = isset($input['CaptionLanguageMappings']) ? array_map([HlsCaptionLanguageMapping::class, 'create'], $input['CaptionLanguageMappings']) : null;
        $this->captionLanguageSetting = $input['CaptionLanguageSetting'] ?? null;
        $this->captionSegmentLengthControl = $input['CaptionSegmentLengthControl'] ?? null;
        $this->clientCache = $input['ClientCache'] ?? null;
        $this->codecSpecification = $input['CodecSpecification'] ?? null;
        $this->destination = $input['Destination'] ?? null;
        $this->destinationSettings = isset($input['DestinationSettings']) ? DestinationSettings::create($input['DestinationSettings']) : null;
        $this->directoryStructure = $input['DirectoryStructure'] ?? null;
        $this->encryption = isset($input['Encryption']) ? HlsEncryptionSettings::create($input['Encryption']) : null;
        $this->imageBasedTrickPlay = $input['ImageBasedTrickPlay'] ?? null;
        $this->imageBasedTrickPlaySettings = isset($input['ImageBasedTrickPlaySettings']) ? HlsImageBasedTrickPlaySettings::create($input['ImageBasedTrickPlaySettings']) : null;
        $this->manifestCompression = $input['ManifestCompression'] ?? null;
        $this->manifestDurationFormat = $input['ManifestDurationFormat'] ?? null;
        $this->minFinalSegmentLength = $input['MinFinalSegmentLength'] ?? null;
        $this->minSegmentLength = $input['MinSegmentLength'] ?? null;
        $this->outputSelection = $input['OutputSelection'] ?? null;
        $this->programDateTime = $input['ProgramDateTime'] ?? null;
        $this->programDateTimePeriod = $input['ProgramDateTimePeriod'] ?? null;
        $this->segmentControl = $input['SegmentControl'] ?? null;
        $this->segmentLength = $input['SegmentLength'] ?? null;
        $this->segmentLengthControl = $input['SegmentLengthControl'] ?? null;
        $this->segmentsPerSubdirectory = $input['SegmentsPerSubdirectory'] ?? null;
        $this->streamInfResolution = $input['StreamInfResolution'] ?? null;
        $this->targetDurationCompatibilityMode = $input['TargetDurationCompatibilityMode'] ?? null;
        $this->timedMetadataId3Frame = $input['TimedMetadataId3Frame'] ?? null;
        $this->timedMetadataId3Period = $input['TimedMetadataId3Period'] ?? null;
        $this->timestampDeltaMilliseconds = $input['TimestampDeltaMilliseconds'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<HlsAdMarkers::*>
     */
    public function getAdMarkers(): array
    {
        return $this->adMarkers ?? [];
    }

    /**
     * @return HlsAdditionalManifest[]
     */
    public function getAdditionalManifests(): array
    {
        return $this->additionalManifests ?? [];
    }

    /**
     * @return HlsAudioOnlyHeader::*|null
     */
    public function getAudioOnlyHeader(): ?string
    {
        return $this->audioOnlyHeader;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    /**
     * @return HlsCaptionLanguageMapping[]
     */
    public function getCaptionLanguageMappings(): array
    {
        return $this->captionLanguageMappings ?? [];
    }

    /**
     * @return HlsCaptionLanguageSetting::*|null
     */
    public function getCaptionLanguageSetting(): ?string
    {
        return $this->captionLanguageSetting;
    }

    /**
     * @return HlsCaptionSegmentLengthControl::*|null
     */
    public function getCaptionSegmentLengthControl(): ?string
    {
        return $this->captionSegmentLengthControl;
    }

    /**
     * @return HlsClientCache::*|null
     */
    public function getClientCache(): ?string
    {
        return $this->clientCache;
    }

    /**
     * @return HlsCodecSpecification::*|null
     */
    public function getCodecSpecification(): ?string
    {
        return $this->codecSpecification;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function getDestinationSettings(): ?DestinationSettings
    {
        return $this->destinationSettings;
    }

    /**
     * @return HlsDirectoryStructure::*|null
     */
    public function getDirectoryStructure(): ?string
    {
        return $this->directoryStructure;
    }

    public function getEncryption(): ?HlsEncryptionSettings
    {
        return $this->encryption;
    }

    /**
     * @return HlsImageBasedTrickPlay::*|null
     */
    public function getImageBasedTrickPlay(): ?string
    {
        return $this->imageBasedTrickPlay;
    }

    public function getImageBasedTrickPlaySettings(): ?HlsImageBasedTrickPlaySettings
    {
        return $this->imageBasedTrickPlaySettings;
    }

    /**
     * @return HlsManifestCompression::*|null
     */
    public function getManifestCompression(): ?string
    {
        return $this->manifestCompression;
    }

    /**
     * @return HlsManifestDurationFormat::*|null
     */
    public function getManifestDurationFormat(): ?string
    {
        return $this->manifestDurationFormat;
    }

    public function getMinFinalSegmentLength(): ?float
    {
        return $this->minFinalSegmentLength;
    }

    public function getMinSegmentLength(): ?int
    {
        return $this->minSegmentLength;
    }

    /**
     * @return HlsOutputSelection::*|null
     */
    public function getOutputSelection(): ?string
    {
        return $this->outputSelection;
    }

    /**
     * @return HlsProgramDateTime::*|null
     */
    public function getProgramDateTime(): ?string
    {
        return $this->programDateTime;
    }

    public function getProgramDateTimePeriod(): ?int
    {
        return $this->programDateTimePeriod;
    }

    /**
     * @return HlsSegmentControl::*|null
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
     * @return HlsSegmentLengthControl::*|null
     */
    public function getSegmentLengthControl(): ?string
    {
        return $this->segmentLengthControl;
    }

    public function getSegmentsPerSubdirectory(): ?int
    {
        return $this->segmentsPerSubdirectory;
    }

    /**
     * @return HlsStreamInfResolution::*|null
     */
    public function getStreamInfResolution(): ?string
    {
        return $this->streamInfResolution;
    }

    /**
     * @return HlsTargetDurationCompatibilityMode::*|null
     */
    public function getTargetDurationCompatibilityMode(): ?string
    {
        return $this->targetDurationCompatibilityMode;
    }

    /**
     * @return HlsTimedMetadataId3Frame::*|null
     */
    public function getTimedMetadataId3Frame(): ?string
    {
        return $this->timedMetadataId3Frame;
    }

    public function getTimedMetadataId3Period(): ?int
    {
        return $this->timedMetadataId3Period;
    }

    public function getTimestampDeltaMilliseconds(): ?int
    {
        return $this->timestampDeltaMilliseconds;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->adMarkers) {
            $index = -1;
            $payload['adMarkers'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!HlsAdMarkers::exists($listValue)) {
                    throw new InvalidArgument(sprintf('Invalid parameter "adMarkers" for "%s". The value "%s" is not a valid "HlsAdMarkers".', __CLASS__, $listValue));
                }
                $payload['adMarkers'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->additionalManifests) {
            $index = -1;
            $payload['additionalManifests'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['additionalManifests'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->audioOnlyHeader) {
            if (!HlsAudioOnlyHeader::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "audioOnlyHeader" for "%s". The value "%s" is not a valid "HlsAudioOnlyHeader".', __CLASS__, $v));
            }
            $payload['audioOnlyHeader'] = $v;
        }
        if (null !== $v = $this->baseUrl) {
            $payload['baseUrl'] = $v;
        }
        if (null !== $v = $this->captionLanguageMappings) {
            $index = -1;
            $payload['captionLanguageMappings'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['captionLanguageMappings'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->captionLanguageSetting) {
            if (!HlsCaptionLanguageSetting::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "captionLanguageSetting" for "%s". The value "%s" is not a valid "HlsCaptionLanguageSetting".', __CLASS__, $v));
            }
            $payload['captionLanguageSetting'] = $v;
        }
        if (null !== $v = $this->captionSegmentLengthControl) {
            if (!HlsCaptionSegmentLengthControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "captionSegmentLengthControl" for "%s". The value "%s" is not a valid "HlsCaptionSegmentLengthControl".', __CLASS__, $v));
            }
            $payload['captionSegmentLengthControl'] = $v;
        }
        if (null !== $v = $this->clientCache) {
            if (!HlsClientCache::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "clientCache" for "%s". The value "%s" is not a valid "HlsClientCache".', __CLASS__, $v));
            }
            $payload['clientCache'] = $v;
        }
        if (null !== $v = $this->codecSpecification) {
            if (!HlsCodecSpecification::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "codecSpecification" for "%s". The value "%s" is not a valid "HlsCodecSpecification".', __CLASS__, $v));
            }
            $payload['codecSpecification'] = $v;
        }
        if (null !== $v = $this->destination) {
            $payload['destination'] = $v;
        }
        if (null !== $v = $this->destinationSettings) {
            $payload['destinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->directoryStructure) {
            if (!HlsDirectoryStructure::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "directoryStructure" for "%s". The value "%s" is not a valid "HlsDirectoryStructure".', __CLASS__, $v));
            }
            $payload['directoryStructure'] = $v;
        }
        if (null !== $v = $this->encryption) {
            $payload['encryption'] = $v->requestBody();
        }
        if (null !== $v = $this->imageBasedTrickPlay) {
            if (!HlsImageBasedTrickPlay::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "imageBasedTrickPlay" for "%s". The value "%s" is not a valid "HlsImageBasedTrickPlay".', __CLASS__, $v));
            }
            $payload['imageBasedTrickPlay'] = $v;
        }
        if (null !== $v = $this->imageBasedTrickPlaySettings) {
            $payload['imageBasedTrickPlaySettings'] = $v->requestBody();
        }
        if (null !== $v = $this->manifestCompression) {
            if (!HlsManifestCompression::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "manifestCompression" for "%s". The value "%s" is not a valid "HlsManifestCompression".', __CLASS__, $v));
            }
            $payload['manifestCompression'] = $v;
        }
        if (null !== $v = $this->manifestDurationFormat) {
            if (!HlsManifestDurationFormat::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "manifestDurationFormat" for "%s". The value "%s" is not a valid "HlsManifestDurationFormat".', __CLASS__, $v));
            }
            $payload['manifestDurationFormat'] = $v;
        }
        if (null !== $v = $this->minFinalSegmentLength) {
            $payload['minFinalSegmentLength'] = $v;
        }
        if (null !== $v = $this->minSegmentLength) {
            $payload['minSegmentLength'] = $v;
        }
        if (null !== $v = $this->outputSelection) {
            if (!HlsOutputSelection::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "outputSelection" for "%s". The value "%s" is not a valid "HlsOutputSelection".', __CLASS__, $v));
            }
            $payload['outputSelection'] = $v;
        }
        if (null !== $v = $this->programDateTime) {
            if (!HlsProgramDateTime::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "programDateTime" for "%s". The value "%s" is not a valid "HlsProgramDateTime".', __CLASS__, $v));
            }
            $payload['programDateTime'] = $v;
        }
        if (null !== $v = $this->programDateTimePeriod) {
            $payload['programDateTimePeriod'] = $v;
        }
        if (null !== $v = $this->segmentControl) {
            if (!HlsSegmentControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "segmentControl" for "%s". The value "%s" is not a valid "HlsSegmentControl".', __CLASS__, $v));
            }
            $payload['segmentControl'] = $v;
        }
        if (null !== $v = $this->segmentLength) {
            $payload['segmentLength'] = $v;
        }
        if (null !== $v = $this->segmentLengthControl) {
            if (!HlsSegmentLengthControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "segmentLengthControl" for "%s". The value "%s" is not a valid "HlsSegmentLengthControl".', __CLASS__, $v));
            }
            $payload['segmentLengthControl'] = $v;
        }
        if (null !== $v = $this->segmentsPerSubdirectory) {
            $payload['segmentsPerSubdirectory'] = $v;
        }
        if (null !== $v = $this->streamInfResolution) {
            if (!HlsStreamInfResolution::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "streamInfResolution" for "%s". The value "%s" is not a valid "HlsStreamInfResolution".', __CLASS__, $v));
            }
            $payload['streamInfResolution'] = $v;
        }
        if (null !== $v = $this->targetDurationCompatibilityMode) {
            if (!HlsTargetDurationCompatibilityMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "targetDurationCompatibilityMode" for "%s". The value "%s" is not a valid "HlsTargetDurationCompatibilityMode".', __CLASS__, $v));
            }
            $payload['targetDurationCompatibilityMode'] = $v;
        }
        if (null !== $v = $this->timedMetadataId3Frame) {
            if (!HlsTimedMetadataId3Frame::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "timedMetadataId3Frame" for "%s". The value "%s" is not a valid "HlsTimedMetadataId3Frame".', __CLASS__, $v));
            }
            $payload['timedMetadataId3Frame'] = $v;
        }
        if (null !== $v = $this->timedMetadataId3Period) {
            $payload['timedMetadataId3Period'] = $v;
        }
        if (null !== $v = $this->timestampDeltaMilliseconds) {
            $payload['timestampDeltaMilliseconds'] = $v;
        }

        return $payload;
    }
}
