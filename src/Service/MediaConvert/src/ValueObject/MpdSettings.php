<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\MpdAccessibilityCaptionHints;
use AsyncAws\MediaConvert\Enum\MpdAudioDuration;
use AsyncAws\MediaConvert\Enum\MpdCaptionContainerType;
use AsyncAws\MediaConvert\Enum\MpdKlvMetadata;
use AsyncAws\MediaConvert\Enum\MpdManifestMetadataSignaling;
use AsyncAws\MediaConvert\Enum\MpdScte35Esam;
use AsyncAws\MediaConvert\Enum\MpdScte35Source;
use AsyncAws\MediaConvert\Enum\MpdTimedMetadata;
use AsyncAws\MediaConvert\Enum\MpdTimedMetadataBoxVersion;

/**
 * These settings relate to the fragmented MP4 container for the segments in your DASH outputs.
 */
final class MpdSettings
{
    /**
     * Optional. Choose Include to have MediaConvert mark up your DASH manifest with `<Accessibility>` elements for embedded
     * 608 captions. This markup isn't generally required, but some video players require it to discover and play embedded
     * 608 captions. Keep the default value, Exclude, to leave these elements out. When you enable this setting, this is the
     * markup that MediaConvert includes in your manifest: `<Accessibility schemeIdUri="urn:scte:dash:cc:cea-608:2015"
     * value="CC1=eng"/>`.
     *
     * @var MpdAccessibilityCaptionHints::*|null
     */
    private $accessibilityCaptionHints;

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
     * @var MpdAudioDuration::*|null
     */
    private $audioDuration;

    /**
     * Use this setting only in DASH output groups that include sidecar TTML, IMSC or WEBVTT captions. You specify sidecar
     * captions in a separate output from your audio and video. Choose Raw for captions in a single XML file in a raw
     * container. Choose Fragmented MPEG-4 for captions in XML format contained within fragmented MP4 files. This set of
     * fragmented MP4 files is separate from your video and audio fragmented MP4 files.
     *
     * @var MpdCaptionContainerType::*|null
     */
    private $captionContainerType;

    /**
     * To include key-length-value metadata in this output: Set KLV metadata insertion to Passthrough. MediaConvert reads
     * KLV metadata present in your input and writes each instance to a separate event message box in the output, according
     * to MISB ST1910.1. To exclude this KLV metadata: Set KLV metadata insertion to None or leave blank.
     *
     * @var MpdKlvMetadata::*|null
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
     * @var MpdManifestMetadataSignaling::*|null
     */
    private $manifestMetadataSignaling;

    /**
     * Use this setting only when you specify SCTE-35 markers from ESAM. Choose INSERT to put SCTE-35 markers in this output
     * at the insertion points that you specify in an ESAM XML document. Provide the document in the setting SCC XML.
     *
     * @var MpdScte35Esam::*|null
     */
    private $scte35Esam;

    /**
     * Ignore this setting unless you have SCTE-35 markers in your input video file. Choose Passthrough if you want SCTE-35
     * markers that appear in your input to also appear in this output. Choose None if you don't want those SCTE-35 markers
     * in this output.
     *
     * @var MpdScte35Source::*|null
     */
    private $scte35Source;

    /**
     * To include ID3 metadata in this output: Set ID3 metadata to Passthrough. Specify this ID3 metadata in Custom ID3
     * metadata inserter. MediaConvert writes each instance of ID3 metadata in a separate Event Message (eMSG) box. To
     * exclude this ID3 metadata: Set ID3 metadata to None or leave blank.
     *
     * @var MpdTimedMetadata::*|null
     */
    private $timedMetadata;

    /**
     * Specify the event message box (eMSG) version for ID3 timed metadata in your output.
     * For more information, see ISO/IEC 23009-1:2022 section 5.10.3.3.3 Syntax.
     * Leave blank to use the default value Version 0.
     * When you specify Version 1, you must also set ID3 metadata to Passthrough.
     *
     * @var MpdTimedMetadataBoxVersion::*|null
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
     *   AccessibilityCaptionHints?: MpdAccessibilityCaptionHints::*|null,
     *   AudioDuration?: MpdAudioDuration::*|null,
     *   CaptionContainerType?: MpdCaptionContainerType::*|null,
     *   KlvMetadata?: MpdKlvMetadata::*|null,
     *   ManifestMetadataSignaling?: MpdManifestMetadataSignaling::*|null,
     *   Scte35Esam?: MpdScte35Esam::*|null,
     *   Scte35Source?: MpdScte35Source::*|null,
     *   TimedMetadata?: MpdTimedMetadata::*|null,
     *   TimedMetadataBoxVersion?: MpdTimedMetadataBoxVersion::*|null,
     *   TimedMetadataSchemeIdUri?: string|null,
     *   TimedMetadataValue?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accessibilityCaptionHints = $input['AccessibilityCaptionHints'] ?? null;
        $this->audioDuration = $input['AudioDuration'] ?? null;
        $this->captionContainerType = $input['CaptionContainerType'] ?? null;
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
     *   AccessibilityCaptionHints?: MpdAccessibilityCaptionHints::*|null,
     *   AudioDuration?: MpdAudioDuration::*|null,
     *   CaptionContainerType?: MpdCaptionContainerType::*|null,
     *   KlvMetadata?: MpdKlvMetadata::*|null,
     *   ManifestMetadataSignaling?: MpdManifestMetadataSignaling::*|null,
     *   Scte35Esam?: MpdScte35Esam::*|null,
     *   Scte35Source?: MpdScte35Source::*|null,
     *   TimedMetadata?: MpdTimedMetadata::*|null,
     *   TimedMetadataBoxVersion?: MpdTimedMetadataBoxVersion::*|null,
     *   TimedMetadataSchemeIdUri?: string|null,
     *   TimedMetadataValue?: string|null,
     * }|MpdSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return MpdAccessibilityCaptionHints::*|null
     */
    public function getAccessibilityCaptionHints(): ?string
    {
        return $this->accessibilityCaptionHints;
    }

    /**
     * @return MpdAudioDuration::*|null
     */
    public function getAudioDuration(): ?string
    {
        return $this->audioDuration;
    }

    /**
     * @return MpdCaptionContainerType::*|null
     */
    public function getCaptionContainerType(): ?string
    {
        return $this->captionContainerType;
    }

    /**
     * @return MpdKlvMetadata::*|null
     */
    public function getKlvMetadata(): ?string
    {
        return $this->klvMetadata;
    }

    /**
     * @return MpdManifestMetadataSignaling::*|null
     */
    public function getManifestMetadataSignaling(): ?string
    {
        return $this->manifestMetadataSignaling;
    }

    /**
     * @return MpdScte35Esam::*|null
     */
    public function getScte35Esam(): ?string
    {
        return $this->scte35Esam;
    }

    /**
     * @return MpdScte35Source::*|null
     */
    public function getScte35Source(): ?string
    {
        return $this->scte35Source;
    }

    /**
     * @return MpdTimedMetadata::*|null
     */
    public function getTimedMetadata(): ?string
    {
        return $this->timedMetadata;
    }

    /**
     * @return MpdTimedMetadataBoxVersion::*|null
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
        if (null !== $v = $this->accessibilityCaptionHints) {
            if (!MpdAccessibilityCaptionHints::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "accessibilityCaptionHints" for "%s". The value "%s" is not a valid "MpdAccessibilityCaptionHints".', __CLASS__, $v));
            }
            $payload['accessibilityCaptionHints'] = $v;
        }
        if (null !== $v = $this->audioDuration) {
            if (!MpdAudioDuration::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDuration" for "%s". The value "%s" is not a valid "MpdAudioDuration".', __CLASS__, $v));
            }
            $payload['audioDuration'] = $v;
        }
        if (null !== $v = $this->captionContainerType) {
            if (!MpdCaptionContainerType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "captionContainerType" for "%s". The value "%s" is not a valid "MpdCaptionContainerType".', __CLASS__, $v));
            }
            $payload['captionContainerType'] = $v;
        }
        if (null !== $v = $this->klvMetadata) {
            if (!MpdKlvMetadata::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "klvMetadata" for "%s". The value "%s" is not a valid "MpdKlvMetadata".', __CLASS__, $v));
            }
            $payload['klvMetadata'] = $v;
        }
        if (null !== $v = $this->manifestMetadataSignaling) {
            if (!MpdManifestMetadataSignaling::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "manifestMetadataSignaling" for "%s". The value "%s" is not a valid "MpdManifestMetadataSignaling".', __CLASS__, $v));
            }
            $payload['manifestMetadataSignaling'] = $v;
        }
        if (null !== $v = $this->scte35Esam) {
            if (!MpdScte35Esam::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scte35Esam" for "%s". The value "%s" is not a valid "MpdScte35Esam".', __CLASS__, $v));
            }
            $payload['scte35Esam'] = $v;
        }
        if (null !== $v = $this->scte35Source) {
            if (!MpdScte35Source::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scte35Source" for "%s". The value "%s" is not a valid "MpdScte35Source".', __CLASS__, $v));
            }
            $payload['scte35Source'] = $v;
        }
        if (null !== $v = $this->timedMetadata) {
            if (!MpdTimedMetadata::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "timedMetadata" for "%s". The value "%s" is not a valid "MpdTimedMetadata".', __CLASS__, $v));
            }
            $payload['timedMetadata'] = $v;
        }
        if (null !== $v = $this->timedMetadataBoxVersion) {
            if (!MpdTimedMetadataBoxVersion::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "timedMetadataBoxVersion" for "%s". The value "%s" is not a valid "MpdTimedMetadataBoxVersion".', __CLASS__, $v));
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
