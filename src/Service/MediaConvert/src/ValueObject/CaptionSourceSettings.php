<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\CaptionSourceType;

/**
 * If your input captions are SCC, TTML, STL, SMI, SRT, or IMSC in an xml file, specify the URI of the input captions
 * source file. If your input captions are IMSC in an IMF package, use TrackSourceSettings instead of FileSoureSettings.
 */
final class CaptionSourceSettings
{
    /**
     * Settings for ancillary captions source.
     *
     * @var AncillarySourceSettings|null
     */
    private $ancillarySourceSettings;

    /**
     * DVB Sub Source Settings.
     *
     * @var DvbSubSourceSettings|null
     */
    private $dvbSubSourceSettings;

    /**
     * Settings for embedded captions Source.
     *
     * @var EmbeddedSourceSettings|null
     */
    private $embeddedSourceSettings;

    /**
     * If your input captions are SCC, SMI, SRT, STL, TTML, WebVTT, or IMSC 1.1 in an xml file, specify the URI of the input
     * caption source file. If your caption source is IMSC in an IMF package, use TrackSourceSettings instead of
     * FileSoureSettings.
     *
     * @var FileSourceSettings|null
     */
    private $fileSourceSettings;

    /**
     * Use Source to identify the format of your input captions. The service cannot auto-detect caption format.
     *
     * @var CaptionSourceType::*|string|null
     */
    private $sourceType;

    /**
     * Settings specific to Teletext caption sources, including Page number.
     *
     * @var TeletextSourceSettings|null
     */
    private $teletextSourceSettings;

    /**
     * Settings specific to caption sources that are specified by track number. Currently, this is only IMSC captions in an
     * IMF package. If your caption source is IMSC 1.1 in a separate xml file, use FileSourceSettings instead of
     * TrackSourceSettings.
     *
     * @var TrackSourceSettings|null
     */
    private $trackSourceSettings;

    /**
     * Settings specific to WebVTT sources in HLS alternative rendition group. Specify the properties (renditionGroupId,
     * renditionName or renditionLanguageCode) to identify the unique subtitle track among the alternative rendition groups
     * present in the HLS manifest. If no unique track is found, or multiple tracks match the specified properties, the job
     * fails. If there is only one subtitle track in the rendition group, the settings can be left empty and the default
     * subtitle track will be chosen. If your caption source is a sidecar file, use FileSourceSettings instead of
     * WebvttHlsSourceSettings.
     *
     * @var WebvttHlsSourceSettings|null
     */
    private $webvttHlsSourceSettings;

    /**
     * @param array{
     *   AncillarySourceSettings?: null|AncillarySourceSettings|array,
     *   DvbSubSourceSettings?: null|DvbSubSourceSettings|array,
     *   EmbeddedSourceSettings?: null|EmbeddedSourceSettings|array,
     *   FileSourceSettings?: null|FileSourceSettings|array,
     *   SourceType?: null|CaptionSourceType::*|string,
     *   TeletextSourceSettings?: null|TeletextSourceSettings|array,
     *   TrackSourceSettings?: null|TrackSourceSettings|array,
     *   WebvttHlsSourceSettings?: null|WebvttHlsSourceSettings|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ancillarySourceSettings = isset($input['AncillarySourceSettings']) ? AncillarySourceSettings::create($input['AncillarySourceSettings']) : null;
        $this->dvbSubSourceSettings = isset($input['DvbSubSourceSettings']) ? DvbSubSourceSettings::create($input['DvbSubSourceSettings']) : null;
        $this->embeddedSourceSettings = isset($input['EmbeddedSourceSettings']) ? EmbeddedSourceSettings::create($input['EmbeddedSourceSettings']) : null;
        $this->fileSourceSettings = isset($input['FileSourceSettings']) ? FileSourceSettings::create($input['FileSourceSettings']) : null;
        $this->sourceType = $input['SourceType'] ?? null;
        $this->teletextSourceSettings = isset($input['TeletextSourceSettings']) ? TeletextSourceSettings::create($input['TeletextSourceSettings']) : null;
        $this->trackSourceSettings = isset($input['TrackSourceSettings']) ? TrackSourceSettings::create($input['TrackSourceSettings']) : null;
        $this->webvttHlsSourceSettings = isset($input['WebvttHlsSourceSettings']) ? WebvttHlsSourceSettings::create($input['WebvttHlsSourceSettings']) : null;
    }

    /**
     * @param array{
     *   AncillarySourceSettings?: null|AncillarySourceSettings|array,
     *   DvbSubSourceSettings?: null|DvbSubSourceSettings|array,
     *   EmbeddedSourceSettings?: null|EmbeddedSourceSettings|array,
     *   FileSourceSettings?: null|FileSourceSettings|array,
     *   SourceType?: null|CaptionSourceType::*|string,
     *   TeletextSourceSettings?: null|TeletextSourceSettings|array,
     *   TrackSourceSettings?: null|TrackSourceSettings|array,
     *   WebvttHlsSourceSettings?: null|WebvttHlsSourceSettings|array,
     * }|CaptionSourceSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAncillarySourceSettings(): ?AncillarySourceSettings
    {
        return $this->ancillarySourceSettings;
    }

    public function getDvbSubSourceSettings(): ?DvbSubSourceSettings
    {
        return $this->dvbSubSourceSettings;
    }

    public function getEmbeddedSourceSettings(): ?EmbeddedSourceSettings
    {
        return $this->embeddedSourceSettings;
    }

    public function getFileSourceSettings(): ?FileSourceSettings
    {
        return $this->fileSourceSettings;
    }

    /**
     * @return CaptionSourceType::*|string|null
     */
    public function getSourceType(): ?string
    {
        return $this->sourceType;
    }

    public function getTeletextSourceSettings(): ?TeletextSourceSettings
    {
        return $this->teletextSourceSettings;
    }

    public function getTrackSourceSettings(): ?TrackSourceSettings
    {
        return $this->trackSourceSettings;
    }

    public function getWebvttHlsSourceSettings(): ?WebvttHlsSourceSettings
    {
        return $this->webvttHlsSourceSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->ancillarySourceSettings) {
            $payload['ancillarySourceSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->dvbSubSourceSettings) {
            $payload['dvbSubSourceSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->embeddedSourceSettings) {
            $payload['embeddedSourceSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->fileSourceSettings) {
            $payload['fileSourceSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->sourceType) {
            if (!CaptionSourceType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "sourceType" for "%s". The value "%s" is not a valid "CaptionSourceType".', __CLASS__, $v));
            }
            $payload['sourceType'] = $v;
        }
        if (null !== $v = $this->teletextSourceSettings) {
            $payload['teletextSourceSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->trackSourceSettings) {
            $payload['trackSourceSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->webvttHlsSourceSettings) {
            $payload['webvttHlsSourceSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
