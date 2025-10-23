<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\ImscAccessibilitySubs;
use AsyncAws\MediaConvert\Enum\ImscStylePassthrough;

/**
 * Settings related to IMSC captions. IMSC is a sidecar format that holds captions in a file that is separate from the
 * video container. Set up sidecar captions in the same output group, but different output from your video. For more
 * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/ttml-and-webvtt-output-captions.html.
 */
final class ImscDestinationSettings
{
    /**
     * If the IMSC captions track is intended to provide accessibility for people who are deaf or hard of hearing: Set
     * Accessibility subtitles to Enabled. When you do, MediaConvert adds accessibility attributes to your output HLS or
     * DASH manifest. For HLS manifests, MediaConvert adds the following accessibility attributes under EXT-X-MEDIA for this
     * track:
     * CHARACTERISTICS="public.accessibility.transcribes-spoken-dialog,public.accessibility.describes-music-and-sound" and
     * AUTOSELECT="YES". For DASH manifests, MediaConvert adds the following in the adaptation set for this track:
     * `<Accessibility schemeIdUri="urn:mpeg:dash:role:2011" value="caption"/>`. If the captions track is not intended to
     * provide such accessibility: Keep the default value, Disabled. When you do, for DASH manifests, MediaConvert instead
     * adds the following in the adaptation set for this track: `<Role schemeIDUri="urn:mpeg:dash:role:2011"
     * value="subtitle"/>`.
     *
     * @var ImscAccessibilitySubs::*|null
     */
    private $accessibility;

    /**
     * Keep this setting enabled to have MediaConvert use the font style and position information from the captions source
     * in the output. This option is available only when your input captions are IMSC, SMPTE-TT, or TTML. Disable this
     * setting for simplified output captions.
     *
     * @var ImscStylePassthrough::*|null
     */
    private $stylePassthrough;

    /**
     * @param array{
     *   Accessibility?: ImscAccessibilitySubs::*|null,
     *   StylePassthrough?: ImscStylePassthrough::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accessibility = $input['Accessibility'] ?? null;
        $this->stylePassthrough = $input['StylePassthrough'] ?? null;
    }

    /**
     * @param array{
     *   Accessibility?: ImscAccessibilitySubs::*|null,
     *   StylePassthrough?: ImscStylePassthrough::*|null,
     * }|ImscDestinationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ImscAccessibilitySubs::*|null
     */
    public function getAccessibility(): ?string
    {
        return $this->accessibility;
    }

    /**
     * @return ImscStylePassthrough::*|null
     */
    public function getStylePassthrough(): ?string
    {
        return $this->stylePassthrough;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->accessibility) {
            if (!ImscAccessibilitySubs::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "accessibility" for "%s". The value "%s" is not a valid "ImscAccessibilitySubs".', __CLASS__, $v));
            }
            $payload['accessibility'] = $v;
        }
        if (null !== $v = $this->stylePassthrough) {
            if (!ImscStylePassthrough::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "stylePassthrough" for "%s". The value "%s" is not a valid "ImscStylePassthrough".', __CLASS__, $v));
            }
            $payload['stylePassthrough'] = $v;
        }

        return $payload;
    }
}
