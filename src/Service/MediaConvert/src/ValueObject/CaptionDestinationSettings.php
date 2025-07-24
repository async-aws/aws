<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\CaptionDestinationType;

/**
 * Settings related to one captions tab on the MediaConvert console. Usually, one captions tab corresponds to one output
 * captions track. Depending on your output captions format, one tab might correspond to a set of output captions
 * tracks. For more information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/including-captions.html.
 */
final class CaptionDestinationSettings
{
    /**
     * Burn-in is a captions delivery method, rather than a captions format. Burn-in writes the captions directly on your
     * video frames, replacing pixels of video content with the captions. Set up burn-in captions in the same output as your
     * video. For more information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/burn-in-output-captions.html.
     *
     * @var BurninDestinationSettings|null
     */
    private $burninDestinationSettings;

    /**
     * Specify the format for this set of captions on this output. The default format is embedded without SCTE-20. Note that
     * your choice of video output container constrains your choice of output captions format. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/captions-support-tables.html. If you are using SCTE-20 and you
     * want to create an output that complies with the SCTE-43 spec, choose SCTE-20 plus embedded. To create a non-compliant
     * output where the embedded captions come first, choose Embedded plus SCTE-20.
     *
     * @var CaptionDestinationType::*|string|null
     */
    private $destinationType;

    /**
     * Settings related to DVB-Sub captions. Set up DVB-Sub captions in the same output as your video. For more information,
     * see https://docs.aws.amazon.com/mediaconvert/latest/ug/dvb-sub-output-captions.html.
     *
     * @var DvbSubDestinationSettings|null
     */
    private $dvbSubDestinationSettings;

    /**
     * Settings related to CEA/EIA-608 and CEA/EIA-708 (also called embedded or ancillary) captions. Set up embedded
     * captions in the same output as your video. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/embedded-output-captions.html.
     *
     * @var EmbeddedDestinationSettings|null
     */
    private $embeddedDestinationSettings;

    /**
     * Settings related to IMSC captions. IMSC is a sidecar format that holds captions in a file that is separate from the
     * video container. Set up sidecar captions in the same output group, but different output from your video. For more
     * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/ttml-and-webvtt-output-captions.html.
     *
     * @var ImscDestinationSettings|null
     */
    private $imscDestinationSettings;

    /**
     * Settings related to SCC captions. SCC is a sidecar format that holds captions in a file that is separate from the
     * video container. Set up sidecar captions in the same output group, but different output from your video. For more
     * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/scc-srt-output-captions.html.
     *
     * @var SccDestinationSettings|null
     */
    private $sccDestinationSettings;

    /**
     * Settings related to SRT captions. SRT is a sidecar format that holds captions in a file that is separate from the
     * video container. Set up sidecar captions in the same output group, but different output from your video.
     *
     * @var SrtDestinationSettings|null
     */
    private $srtDestinationSettings;

    /**
     * Settings related to teletext captions. Set up teletext captions in the same output as your video. For more
     * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/teletext-output-captions.html.
     *
     * @var TeletextDestinationSettings|null
     */
    private $teletextDestinationSettings;

    /**
     * Settings related to TTML captions. TTML is a sidecar format that holds captions in a file that is separate from the
     * video container. Set up sidecar captions in the same output group, but different output from your video. For more
     * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/ttml-and-webvtt-output-captions.html.
     *
     * @var TtmlDestinationSettings|null
     */
    private $ttmlDestinationSettings;

    /**
     * Settings related to WebVTT captions. WebVTT is a sidecar format that holds captions in a file that is separate from
     * the video container. Set up sidecar captions in the same output group, but different output from your video. For more
     * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/ttml-and-webvtt-output-captions.html.
     *
     * @var WebvttDestinationSettings|null
     */
    private $webvttDestinationSettings;

    /**
     * @param array{
     *   BurninDestinationSettings?: null|BurninDestinationSettings|array,
     *   DestinationType?: null|CaptionDestinationType::*|string,
     *   DvbSubDestinationSettings?: null|DvbSubDestinationSettings|array,
     *   EmbeddedDestinationSettings?: null|EmbeddedDestinationSettings|array,
     *   ImscDestinationSettings?: null|ImscDestinationSettings|array,
     *   SccDestinationSettings?: null|SccDestinationSettings|array,
     *   SrtDestinationSettings?: null|SrtDestinationSettings|array,
     *   TeletextDestinationSettings?: null|TeletextDestinationSettings|array,
     *   TtmlDestinationSettings?: null|TtmlDestinationSettings|array,
     *   WebvttDestinationSettings?: null|WebvttDestinationSettings|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->burninDestinationSettings = isset($input['BurninDestinationSettings']) ? BurninDestinationSettings::create($input['BurninDestinationSettings']) : null;
        $this->destinationType = $input['DestinationType'] ?? null;
        $this->dvbSubDestinationSettings = isset($input['DvbSubDestinationSettings']) ? DvbSubDestinationSettings::create($input['DvbSubDestinationSettings']) : null;
        $this->embeddedDestinationSettings = isset($input['EmbeddedDestinationSettings']) ? EmbeddedDestinationSettings::create($input['EmbeddedDestinationSettings']) : null;
        $this->imscDestinationSettings = isset($input['ImscDestinationSettings']) ? ImscDestinationSettings::create($input['ImscDestinationSettings']) : null;
        $this->sccDestinationSettings = isset($input['SccDestinationSettings']) ? SccDestinationSettings::create($input['SccDestinationSettings']) : null;
        $this->srtDestinationSettings = isset($input['SrtDestinationSettings']) ? SrtDestinationSettings::create($input['SrtDestinationSettings']) : null;
        $this->teletextDestinationSettings = isset($input['TeletextDestinationSettings']) ? TeletextDestinationSettings::create($input['TeletextDestinationSettings']) : null;
        $this->ttmlDestinationSettings = isset($input['TtmlDestinationSettings']) ? TtmlDestinationSettings::create($input['TtmlDestinationSettings']) : null;
        $this->webvttDestinationSettings = isset($input['WebvttDestinationSettings']) ? WebvttDestinationSettings::create($input['WebvttDestinationSettings']) : null;
    }

    /**
     * @param array{
     *   BurninDestinationSettings?: null|BurninDestinationSettings|array,
     *   DestinationType?: null|CaptionDestinationType::*|string,
     *   DvbSubDestinationSettings?: null|DvbSubDestinationSettings|array,
     *   EmbeddedDestinationSettings?: null|EmbeddedDestinationSettings|array,
     *   ImscDestinationSettings?: null|ImscDestinationSettings|array,
     *   SccDestinationSettings?: null|SccDestinationSettings|array,
     *   SrtDestinationSettings?: null|SrtDestinationSettings|array,
     *   TeletextDestinationSettings?: null|TeletextDestinationSettings|array,
     *   TtmlDestinationSettings?: null|TtmlDestinationSettings|array,
     *   WebvttDestinationSettings?: null|WebvttDestinationSettings|array,
     * }|CaptionDestinationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBurninDestinationSettings(): ?BurninDestinationSettings
    {
        return $this->burninDestinationSettings;
    }

    /**
     * @return CaptionDestinationType::*|string|null
     */
    public function getDestinationType(): ?string
    {
        return $this->destinationType;
    }

    public function getDvbSubDestinationSettings(): ?DvbSubDestinationSettings
    {
        return $this->dvbSubDestinationSettings;
    }

    public function getEmbeddedDestinationSettings(): ?EmbeddedDestinationSettings
    {
        return $this->embeddedDestinationSettings;
    }

    public function getImscDestinationSettings(): ?ImscDestinationSettings
    {
        return $this->imscDestinationSettings;
    }

    public function getSccDestinationSettings(): ?SccDestinationSettings
    {
        return $this->sccDestinationSettings;
    }

    public function getSrtDestinationSettings(): ?SrtDestinationSettings
    {
        return $this->srtDestinationSettings;
    }

    public function getTeletextDestinationSettings(): ?TeletextDestinationSettings
    {
        return $this->teletextDestinationSettings;
    }

    public function getTtmlDestinationSettings(): ?TtmlDestinationSettings
    {
        return $this->ttmlDestinationSettings;
    }

    public function getWebvttDestinationSettings(): ?WebvttDestinationSettings
    {
        return $this->webvttDestinationSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->burninDestinationSettings) {
            $payload['burninDestinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->destinationType) {
            if (!CaptionDestinationType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "destinationType" for "%s". The value "%s" is not a valid "CaptionDestinationType".', __CLASS__, $v));
            }
            $payload['destinationType'] = $v;
        }
        if (null !== $v = $this->dvbSubDestinationSettings) {
            $payload['dvbSubDestinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->embeddedDestinationSettings) {
            $payload['embeddedDestinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->imscDestinationSettings) {
            $payload['imscDestinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->sccDestinationSettings) {
            $payload['sccDestinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->srtDestinationSettings) {
            $payload['srtDestinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->teletextDestinationSettings) {
            $payload['teletextDestinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->ttmlDestinationSettings) {
            $payload['ttmlDestinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->webvttDestinationSettings) {
            $payload['webvttDestinationSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
