<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
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

/**
 * These settings relate to the fragmented MP4 container for the segments in your CMAF outputs.
 */
final class CmfcSettings
{
    /**
     * Specify this setting only when your output will be consumed by a downstream repackaging workflow that is sensitive to
     * very small duration differences between video and audio. For this situation, choose Match video duration. In all
     * other cases, keep the default value, Default codec duration. When you choose Match video duration, MediaConvert pads
     * the output audio streams with silence or trims them to ensure that the total duration of each audio stream is at
     * least as long as the total duration of the video stream. After padding or trimming, the audio stream duration is no
     * more than one frame longer than the video stream. MediaConvert applies audio padding or trimming only to the end of
     * the last segment of the output. For unsegmented outputs, MediaConvert adds padding only to the end of the file. When
     * you keep the default value, any minor discrepancies between audio and video duration will depend on your output audio
     * codec.
     *
     * @var CmfcAudioDuration::*|string|null
     */
    private $audioDuration;

    /**
     * Specify the audio rendition group for this audio rendition. Specify up to one value for each audio output in your
     * output group. This value appears in your HLS parent manifest in the EXT-X-MEDIA tag of TYPE=AUDIO, as the value for
     * the GROUP-ID attribute. For example, if you specify "audio_aac_1" for Audio group ID, it appears in your manifest
     * like this: #EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="audio_aac_1". Related setting: To associate the rendition group that
     * this audio track belongs to with a video rendition, include the same value that you provide here for that video
     * output's setting Audio rendition sets.
     *
     * @var string|null
     */
    private $audioGroupId;

    /**
     * List the audio rendition groups that you want included with this video rendition. Use a comma-separated list. For
     * example, say you want to include the audio rendition groups that have the audio group IDs "audio_aac_1" and
     * "audio_dolby". Then you would specify this value: "audio_aac_1,audio_dolby". Related setting: The rendition groups
     * that you include in your comma-separated list should all match values that you specify in the setting Audio group ID
     * for audio renditions in the same output group as this video rendition. Default behavior: If you don't specify
     * anything here and for Audio group ID, MediaConvert puts each audio variant in its own audio rendition group and
     * associates it with every video variant. Each value in your list appears in your HLS parent manifest in the
     * EXT-X-STREAM-INF tag as the value for the AUDIO attribute. To continue the previous example, say that the file name
     * for the child manifest for your video rendition is "amazing_video_1.m3u8". Then, in your parent manifest, each value
     * will appear on separate lines, like this: #EXT-X-STREAM-INF:AUDIO="audio_aac_1"... amazing_video_1.m3u8
     * #EXT-X-STREAM-INF:AUDIO="audio_dolby"... amazing_video_1.m3u8.
     *
     * @var string|null
     */
    private $audioRenditionSets;

    /**
     * Use this setting to control the values that MediaConvert puts in your HLS parent playlist to control how the client
     * player selects which audio track to play. Choose Audio-only variant stream (AUDIO_ONLY_VARIANT_STREAM) for any
     * variant that you want to prohibit the client from playing with video. This causes MediaConvert to represent the
     * variant as an EXT-X-STREAM-INF in the HLS manifest. The other options for this setting determine the values that
     * MediaConvert writes for the DEFAULT and AUTOSELECT attributes of the EXT-X-MEDIA entry for the audio variant. For
     * more information about these attributes, see the Apple documentation article
     * https://developer.apple.com/documentation/http_live_streaming/example_playlists_for_http_live_streaming/adding_alternate_media_to_a_playlist.
     * Choose Alternate audio, auto select, default to set DEFAULT=YES and AUTOSELECT=YES. Choose this value for only one
     * variant in your output group. Choose Alternate audio, auto select, not default to set DEFAULT=NO and AUTOSELECT=YES.
     * Choose Alternate Audio, Not Auto Select to set DEFAULT=NO and AUTOSELECT=NO. When you don't specify a value for this
     * setting, MediaConvert defaults to Alternate audio, auto select, default. When there is more than one variant in your
     * output group, you must explicitly choose a value for this setting.
     *
     * @var CmfcAudioTrackType::*|string|null
     */
    private $audioTrackType;

    /**
     * Specify whether to flag this audio track as descriptive video service (DVS) in your HLS parent manifest. When you
     * choose Flag, MediaConvert includes the parameter CHARACTERISTICS="public.accessibility.describes-video" in the
     * EXT-X-MEDIA entry for this track. When you keep the default choice, Don't flag, MediaConvert leaves this parameter
     * out. The DVS flag can help with accessibility on Apple devices. For more information, see the Apple documentation.
     *
     * @var CmfcDescriptiveVideoServiceFlag::*|string|null
     */
    private $descriptiveVideoServiceFlag;

    /**
     * Choose Include to have MediaConvert generate an HLS child manifest that lists only the I-frames for this rendition,
     * in addition to your regular manifest for this rendition. You might use this manifest as part of a workflow that
     * creates preview functions for your video. MediaConvert adds both the I-frame only child manifest and the regular
     * child manifest to the parent manifest. When you don't need the I-frame only child manifest, keep the default value
     * Exclude.
     *
     * @var CmfcIFrameOnlyManifest::*|string|null
     */
    private $iframeOnlyManifest;

    /**
     * To include key-length-value metadata in this output: Set KLV metadata insertion to Passthrough. MediaConvert reads
     * KLV metadata present in your input and writes each instance to a separate event message box in the output, according
     * to MISB ST1910.1. To exclude this KLV metadata: Set KLV metadata insertion to None or leave blank.
     *
     * @var CmfcKlvMetadata::*|string|null
     */
    private $klvMetadata;

    /**
     * To add an InbandEventStream element in your output MPD manifest for each type of event message, set Manifest metadata
     * signaling to Enabled. For ID3 event messages, the InbandEventStream element schemeIdUri will be same value that you
     * specify for ID3 metadata scheme ID URI. For SCTE35 event messages, the InbandEventStream element schemeIdUri will be
     * "urn:scte:scte35:2013:bin". To leave these elements out of your output MPD manifest, set Manifest metadata signaling
     * to Disabled. To enable Manifest metadata signaling, you must also set SCTE-35 source to Passthrough, ESAM SCTE-35 to
     * insert, or ID3 metadata to Passthrough.
     *
     * @var CmfcManifestMetadataSignaling::*|string|null
     */
    private $manifestMetadataSignaling;

    /**
     * Use this setting only when you specify SCTE-35 markers from ESAM. Choose INSERT to put SCTE-35 markers in this output
     * at the insertion points that you specify in an ESAM XML document. Provide the document in the setting SCC XML.
     *
     * @var CmfcScte35Esam::*|string|null
     */
    private $scte35Esam;

    /**
     * Ignore this setting unless you have SCTE-35 markers in your input video file. Choose Passthrough if you want SCTE-35
     * markers that appear in your input to also appear in this output. Choose None if you don't want those SCTE-35 markers
     * in this output.
     *
     * @var CmfcScte35Source::*|string|null
     */
    private $scte35Source;

    /**
     * To include ID3 metadata in this output: Set ID3 metadata to Passthrough. Specify this ID3 metadata in Custom ID3
     * metadata inserter. MediaConvert writes each instance of ID3 metadata in a separate Event Message (eMSG) box. To
     * exclude this ID3 metadata: Set ID3 metadata to None or leave blank.
     *
     * @var CmfcTimedMetadata::*|string|null
     */
    private $timedMetadata;

    /**
     * Specify the event message box (eMSG) version for ID3 timed metadata in your output.
     * For more information, see ISO/IEC 23009-1:2022 section 5.10.3.3.3 Syntax.
     * Leave blank to use the default value Version 0.
     * When you specify Version 1, you must also set ID3 metadata to Passthrough.
     *
     * @var CmfcTimedMetadataBoxVersion::*|string|null
     */
    private $timedMetadataBoxVersion;

    /**
     * Specify the event message box (eMSG) scheme ID URI for ID3 timed metadata in your output. For more information, see
     * ISO/IEC 23009-1:2022 section 5.10.3.3.4 Semantics. Leave blank to use the default value: https://aomedia.org/emsg/ID3
     * When you specify a value for ID3 metadata scheme ID URI, you must also set ID3 metadata to Passthrough.
     *
     * @var string|null
     */
    private $timedMetadataSchemeIdUri;

    /**
     * Specify the event message box (eMSG) value for ID3 timed metadata in your output. For more information, see ISO/IEC
     * 23009-1:2022 section 5.10.3.3.4 Semantics. When you specify a value for ID3 Metadata Value, you must also set ID3
     * metadata to Passthrough.
     *
     * @var string|null
     */
    private $timedMetadataValue;

    /**
     * @param array{
     *   AudioDuration?: null|CmfcAudioDuration::*|string,
     *   AudioGroupId?: null|string,
     *   AudioRenditionSets?: null|string,
     *   AudioTrackType?: null|CmfcAudioTrackType::*|string,
     *   DescriptiveVideoServiceFlag?: null|CmfcDescriptiveVideoServiceFlag::*|string,
     *   IFrameOnlyManifest?: null|CmfcIFrameOnlyManifest::*|string,
     *   KlvMetadata?: null|CmfcKlvMetadata::*|string,
     *   ManifestMetadataSignaling?: null|CmfcManifestMetadataSignaling::*|string,
     *   Scte35Esam?: null|CmfcScte35Esam::*|string,
     *   Scte35Source?: null|CmfcScte35Source::*|string,
     *   TimedMetadata?: null|CmfcTimedMetadata::*|string,
     *   TimedMetadataBoxVersion?: null|CmfcTimedMetadataBoxVersion::*|string,
     *   TimedMetadataSchemeIdUri?: null|string,
     *   TimedMetadataValue?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioDuration = $input['AudioDuration'] ?? null;
        $this->audioGroupId = $input['AudioGroupId'] ?? null;
        $this->audioRenditionSets = $input['AudioRenditionSets'] ?? null;
        $this->audioTrackType = $input['AudioTrackType'] ?? null;
        $this->descriptiveVideoServiceFlag = $input['DescriptiveVideoServiceFlag'] ?? null;
        $this->iframeOnlyManifest = $input['IFrameOnlyManifest'] ?? null;
        $this->klvMetadata = $input['KlvMetadata'] ?? null;
        $this->manifestMetadataSignaling = $input['ManifestMetadataSignaling'] ?? null;
        $this->scte35Esam = $input['Scte35Esam'] ?? null;
        $this->scte35Source = $input['Scte35Source'] ?? null;
        $this->timedMetadata = $input['TimedMetadata'] ?? null;
        $this->timedMetadataBoxVersion = $input['TimedMetadataBoxVersion'] ?? null;
        $this->timedMetadataSchemeIdUri = $input['TimedMetadataSchemeIdUri'] ?? null;
        $this->timedMetadataValue = $input['TimedMetadataValue'] ?? null;
    }

    /**
     * @param array{
     *   AudioDuration?: null|CmfcAudioDuration::*|string,
     *   AudioGroupId?: null|string,
     *   AudioRenditionSets?: null|string,
     *   AudioTrackType?: null|CmfcAudioTrackType::*|string,
     *   DescriptiveVideoServiceFlag?: null|CmfcDescriptiveVideoServiceFlag::*|string,
     *   IFrameOnlyManifest?: null|CmfcIFrameOnlyManifest::*|string,
     *   KlvMetadata?: null|CmfcKlvMetadata::*|string,
     *   ManifestMetadataSignaling?: null|CmfcManifestMetadataSignaling::*|string,
     *   Scte35Esam?: null|CmfcScte35Esam::*|string,
     *   Scte35Source?: null|CmfcScte35Source::*|string,
     *   TimedMetadata?: null|CmfcTimedMetadata::*|string,
     *   TimedMetadataBoxVersion?: null|CmfcTimedMetadataBoxVersion::*|string,
     *   TimedMetadataSchemeIdUri?: null|string,
     *   TimedMetadataValue?: null|string,
     * }|CmfcSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return CmfcAudioDuration::*|string|null
     */
    public function getAudioDuration(): ?string
    {
        return $this->audioDuration;
    }

    public function getAudioGroupId(): ?string
    {
        return $this->audioGroupId;
    }

    public function getAudioRenditionSets(): ?string
    {
        return $this->audioRenditionSets;
    }

    /**
     * @return CmfcAudioTrackType::*|string|null
     */
    public function getAudioTrackType(): ?string
    {
        return $this->audioTrackType;
    }

    /**
     * @return CmfcDescriptiveVideoServiceFlag::*|string|null
     */
    public function getDescriptiveVideoServiceFlag(): ?string
    {
        return $this->descriptiveVideoServiceFlag;
    }

    /**
     * @return CmfcIFrameOnlyManifest::*|string|null
     */
    public function getIframeOnlyManifest(): ?string
    {
        return $this->iframeOnlyManifest;
    }

    /**
     * @return CmfcKlvMetadata::*|string|null
     */
    public function getKlvMetadata(): ?string
    {
        return $this->klvMetadata;
    }

    /**
     * @return CmfcManifestMetadataSignaling::*|string|null
     */
    public function getManifestMetadataSignaling(): ?string
    {
        return $this->manifestMetadataSignaling;
    }

    /**
     * @return CmfcScte35Esam::*|string|null
     */
    public function getScte35Esam(): ?string
    {
        return $this->scte35Esam;
    }

    /**
     * @return CmfcScte35Source::*|string|null
     */
    public function getScte35Source(): ?string
    {
        return $this->scte35Source;
    }

    /**
     * @return CmfcTimedMetadata::*|string|null
     */
    public function getTimedMetadata(): ?string
    {
        return $this->timedMetadata;
    }

    /**
     * @return CmfcTimedMetadataBoxVersion::*|string|null
     */
    public function getTimedMetadataBoxVersion(): ?string
    {
        return $this->timedMetadataBoxVersion;
    }

    public function getTimedMetadataSchemeIdUri(): ?string
    {
        return $this->timedMetadataSchemeIdUri;
    }

    public function getTimedMetadataValue(): ?string
    {
        return $this->timedMetadataValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioDuration) {
            if (!CmfcAudioDuration::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDuration" for "%s". The value "%s" is not a valid "CmfcAudioDuration".', __CLASS__, $v));
            }
            $payload['audioDuration'] = $v;
        }
        if (null !== $v = $this->audioGroupId) {
            $payload['audioGroupId'] = $v;
        }
        if (null !== $v = $this->audioRenditionSets) {
            $payload['audioRenditionSets'] = $v;
        }
        if (null !== $v = $this->audioTrackType) {
            if (!CmfcAudioTrackType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioTrackType" for "%s". The value "%s" is not a valid "CmfcAudioTrackType".', __CLASS__, $v));
            }
            $payload['audioTrackType'] = $v;
        }
        if (null !== $v = $this->descriptiveVideoServiceFlag) {
            if (!CmfcDescriptiveVideoServiceFlag::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "descriptiveVideoServiceFlag" for "%s". The value "%s" is not a valid "CmfcDescriptiveVideoServiceFlag".', __CLASS__, $v));
            }
            $payload['descriptiveVideoServiceFlag'] = $v;
        }
        if (null !== $v = $this->iframeOnlyManifest) {
            if (!CmfcIFrameOnlyManifest::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "iFrameOnlyManifest" for "%s". The value "%s" is not a valid "CmfcIFrameOnlyManifest".', __CLASS__, $v));
            }
            $payload['iFrameOnlyManifest'] = $v;
        }
        if (null !== $v = $this->klvMetadata) {
            if (!CmfcKlvMetadata::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "klvMetadata" for "%s". The value "%s" is not a valid "CmfcKlvMetadata".', __CLASS__, $v));
            }
            $payload['klvMetadata'] = $v;
        }
        if (null !== $v = $this->manifestMetadataSignaling) {
            if (!CmfcManifestMetadataSignaling::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "manifestMetadataSignaling" for "%s". The value "%s" is not a valid "CmfcManifestMetadataSignaling".', __CLASS__, $v));
            }
            $payload['manifestMetadataSignaling'] = $v;
        }
        if (null !== $v = $this->scte35Esam) {
            if (!CmfcScte35Esam::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scte35Esam" for "%s". The value "%s" is not a valid "CmfcScte35Esam".', __CLASS__, $v));
            }
            $payload['scte35Esam'] = $v;
        }
        if (null !== $v = $this->scte35Source) {
            if (!CmfcScte35Source::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scte35Source" for "%s". The value "%s" is not a valid "CmfcScte35Source".', __CLASS__, $v));
            }
            $payload['scte35Source'] = $v;
        }
        if (null !== $v = $this->timedMetadata) {
            if (!CmfcTimedMetadata::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "timedMetadata" for "%s". The value "%s" is not a valid "CmfcTimedMetadata".', __CLASS__, $v));
            }
            $payload['timedMetadata'] = $v;
        }
        if (null !== $v = $this->timedMetadataBoxVersion) {
            if (!CmfcTimedMetadataBoxVersion::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "timedMetadataBoxVersion" for "%s". The value "%s" is not a valid "CmfcTimedMetadataBoxVersion".', __CLASS__, $v));
            }
            $payload['timedMetadataBoxVersion'] = $v;
        }
        if (null !== $v = $this->timedMetadataSchemeIdUri) {
            $payload['timedMetadataSchemeIdUri'] = $v;
        }
        if (null !== $v = $this->timedMetadataValue) {
            $payload['timedMetadataValue'] = $v;
        }

        return $payload;
    }
}
