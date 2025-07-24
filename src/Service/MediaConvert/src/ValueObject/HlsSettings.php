<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\HlsAudioOnlyContainer;
use AsyncAws\MediaConvert\Enum\HlsAudioTrackType;
use AsyncAws\MediaConvert\Enum\HlsDescriptiveVideoServiceFlag;
use AsyncAws\MediaConvert\Enum\HlsIFrameOnlyManifest;

/**
 * Settings for HLS output groups.
 */
final class HlsSettings
{
    /**
     * Specifies the group to which the audio rendition belongs.
     *
     * @var string|null
     */
    private $audioGroupId;

    /**
     * Use this setting only in audio-only outputs. Choose MPEG-2 Transport Stream (M2TS) to create a file in an MPEG2-TS
     * container. Keep the default value Automatic to create an audio-only file in a raw container. Regardless of the value
     * that you specify here, if this output has video, the service will place the output into an MPEG2-TS container.
     *
     * @var HlsAudioOnlyContainer::*|string|null
     */
    private $audioOnlyContainer;

    /**
     * List all the audio groups that are used with the video output stream. Input all the audio GROUP-IDs that are
     * associated to the video, separate by ','.
     *
     * @var string|null
     */
    private $audioRenditionSets;

    /**
     * Four types of audio-only tracks are supported: Audio-Only Variant Stream The client can play back this audio-only
     * stream instead of video in low-bandwidth scenarios. Represented as an EXT-X-STREAM-INF in the HLS manifest. Alternate
     * Audio, Auto Select, Default Alternate rendition that the client should try to play back by default. Represented as an
     * EXT-X-MEDIA in the HLS manifest with DEFAULT=YES, AUTOSELECT=YES Alternate Audio, Auto Select, Not Default Alternate
     * rendition that the client may try to play back by default. Represented as an EXT-X-MEDIA in the HLS manifest with
     * DEFAULT=NO, AUTOSELECT=YES Alternate Audio, not Auto Select Alternate rendition that the client will not try to play
     * back by default. Represented as an EXT-X-MEDIA in the HLS manifest with DEFAULT=NO, AUTOSELECT=NO.
     *
     * @var HlsAudioTrackType::*|string|null
     */
    private $audioTrackType;

    /**
     * Specify whether to flag this audio track as descriptive video service (DVS) in your HLS parent manifest. When you
     * choose Flag, MediaConvert includes the parameter CHARACTERISTICS="public.accessibility.describes-video" in the
     * EXT-X-MEDIA entry for this track. When you keep the default choice, Don't flag, MediaConvert leaves this parameter
     * out. The DVS flag can help with accessibility on Apple devices. For more information, see the Apple documentation.
     *
     * @var HlsDescriptiveVideoServiceFlag::*|string|null
     */
    private $descriptiveVideoServiceFlag;

    /**
     * Choose Include to have MediaConvert generate a child manifest that lists only the I-frames for this rendition, in
     * addition to your regular manifest for this rendition. You might use this manifest as part of a workflow that creates
     * preview functions for your video. MediaConvert adds both the I-frame only child manifest and the regular child
     * manifest to the parent manifest. When you don't need the I-frame only child manifest, keep the default value Exclude.
     *
     * @var HlsIFrameOnlyManifest::*|string|null
     */
    private $iframeOnlyManifest;

    /**
     * Use this setting to add an identifying string to the filename of each segment. The service adds this string between
     * the name modifier and segment index number. You can use format identifiers in the string. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/using-variables-in-your-job-settings.html.
     *
     * @var string|null
     */
    private $segmentModifier;

    /**
     * @param array{
     *   AudioGroupId?: null|string,
     *   AudioOnlyContainer?: null|HlsAudioOnlyContainer::*|string,
     *   AudioRenditionSets?: null|string,
     *   AudioTrackType?: null|HlsAudioTrackType::*|string,
     *   DescriptiveVideoServiceFlag?: null|HlsDescriptiveVideoServiceFlag::*|string,
     *   IFrameOnlyManifest?: null|HlsIFrameOnlyManifest::*|string,
     *   SegmentModifier?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioGroupId = $input['AudioGroupId'] ?? null;
        $this->audioOnlyContainer = $input['AudioOnlyContainer'] ?? null;
        $this->audioRenditionSets = $input['AudioRenditionSets'] ?? null;
        $this->audioTrackType = $input['AudioTrackType'] ?? null;
        $this->descriptiveVideoServiceFlag = $input['DescriptiveVideoServiceFlag'] ?? null;
        $this->iframeOnlyManifest = $input['IFrameOnlyManifest'] ?? null;
        $this->segmentModifier = $input['SegmentModifier'] ?? null;
    }

    /**
     * @param array{
     *   AudioGroupId?: null|string,
     *   AudioOnlyContainer?: null|HlsAudioOnlyContainer::*|string,
     *   AudioRenditionSets?: null|string,
     *   AudioTrackType?: null|HlsAudioTrackType::*|string,
     *   DescriptiveVideoServiceFlag?: null|HlsDescriptiveVideoServiceFlag::*|string,
     *   IFrameOnlyManifest?: null|HlsIFrameOnlyManifest::*|string,
     *   SegmentModifier?: null|string,
     * }|HlsSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAudioGroupId(): ?string
    {
        return $this->audioGroupId;
    }

    /**
     * @return HlsAudioOnlyContainer::*|string|null
     */
    public function getAudioOnlyContainer(): ?string
    {
        return $this->audioOnlyContainer;
    }

    public function getAudioRenditionSets(): ?string
    {
        return $this->audioRenditionSets;
    }

    /**
     * @return HlsAudioTrackType::*|string|null
     */
    public function getAudioTrackType(): ?string
    {
        return $this->audioTrackType;
    }

    /**
     * @return HlsDescriptiveVideoServiceFlag::*|string|null
     */
    public function getDescriptiveVideoServiceFlag(): ?string
    {
        return $this->descriptiveVideoServiceFlag;
    }

    /**
     * @return HlsIFrameOnlyManifest::*|string|null
     */
    public function getIframeOnlyManifest(): ?string
    {
        return $this->iframeOnlyManifest;
    }

    public function getSegmentModifier(): ?string
    {
        return $this->segmentModifier;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioGroupId) {
            $payload['audioGroupId'] = $v;
        }
        if (null !== $v = $this->audioOnlyContainer) {
            if (!HlsAudioOnlyContainer::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioOnlyContainer" for "%s". The value "%s" is not a valid "HlsAudioOnlyContainer".', __CLASS__, $v));
            }
            $payload['audioOnlyContainer'] = $v;
        }
        if (null !== $v = $this->audioRenditionSets) {
            $payload['audioRenditionSets'] = $v;
        }
        if (null !== $v = $this->audioTrackType) {
            if (!HlsAudioTrackType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioTrackType" for "%s". The value "%s" is not a valid "HlsAudioTrackType".', __CLASS__, $v));
            }
            $payload['audioTrackType'] = $v;
        }
        if (null !== $v = $this->descriptiveVideoServiceFlag) {
            if (!HlsDescriptiveVideoServiceFlag::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "descriptiveVideoServiceFlag" for "%s". The value "%s" is not a valid "HlsDescriptiveVideoServiceFlag".', __CLASS__, $v));
            }
            $payload['descriptiveVideoServiceFlag'] = $v;
        }
        if (null !== $v = $this->iframeOnlyManifest) {
            if (!HlsIFrameOnlyManifest::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "iFrameOnlyManifest" for "%s". The value "%s" is not a valid "HlsIFrameOnlyManifest".', __CLASS__, $v));
            }
            $payload['iFrameOnlyManifest'] = $v;
        }
        if (null !== $v = $this->segmentModifier) {
            $payload['segmentModifier'] = $v;
        }

        return $payload;
    }
}
