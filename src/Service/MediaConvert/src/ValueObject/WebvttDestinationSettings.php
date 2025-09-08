<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\WebvttAccessibilitySubs;
use AsyncAws\MediaConvert\Enum\WebvttStylePassthrough;

/**
 * Settings related to WebVTT captions. WebVTT is a sidecar format that holds captions in a file that is separate from
 * the video container. Set up sidecar captions in the same output group, but different output from your video. For more
 * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/ttml-and-webvtt-output-captions.html.
 */
final class WebvttDestinationSettings
{
    /**
     * If the WebVTT captions track is intended to provide accessibility for people who are deaf or hard of hearing: Set
     * Accessibility subtitles to Enabled. When you do, MediaConvert adds accessibility attributes to your output HLS or
     * DASH manifest. For HLS manifests, MediaConvert adds the following accessibility attributes under EXT-X-MEDIA for this
     * track: CHARACTERISTICS="public.accessibility.describes-spoken-dialog,public.accessibility.describes-music-and-sound"
     * and AUTOSELECT="YES". For DASH manifests, MediaConvert adds the following in the adaptation set for this track:
     * `<Accessibility schemeIdUri="urn:mpeg:dash:role:2011" value="caption"/>`. If the captions track is not intended to
     * provide such accessibility: Keep the default value, Disabled. When you do, for DASH manifests, MediaConvert instead
     * adds the following in the adaptation set for this track: `<Role schemeIDUri="urn:mpeg:dash:role:2011"
     * value="subtitle"/>`.
     *
     * @var WebvttAccessibilitySubs::*|null
     */
    private $accessibility;

    /**
     * Specify how MediaConvert writes style information in your output WebVTT captions. To use the available style, color,
     * and position information from your input captions: Choose Enabled. MediaConvert uses default settings when style and
     * position information is missing from your input captions. To recreate the input captions exactly: Choose Strict.
     * MediaConvert automatically applies timing adjustments, including adjustments for frame rate conversion, ad avails,
     * and input clipping. Your input captions format must be WebVTT. To ignore the style and position information from your
     * input captions and use simplified output captions: Keep the default value, Disabled. Or leave blank. To use the
     * available style, color, and position information from your input captions, while merging cues with identical time
     * ranges: Choose merge. This setting can help prevent positioning overlaps for certain players that expect a single
     * single cue for any given time range.
     *
     * @var WebvttStylePassthrough::*|null
     */
    private $stylePassthrough;

    /**
     * @param array{
     *   Accessibility?: WebvttAccessibilitySubs::*|null,
     *   StylePassthrough?: WebvttStylePassthrough::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accessibility = $input['Accessibility'] ?? null;
        $this->stylePassthrough = $input['StylePassthrough'] ?? null;
    }

    /**
     * @param array{
     *   Accessibility?: WebvttAccessibilitySubs::*|null,
     *   StylePassthrough?: WebvttStylePassthrough::*|null,
     * }|WebvttDestinationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return WebvttAccessibilitySubs::*|null
     */
    public function getAccessibility(): ?string
    {
        return $this->accessibility;
    }

    /**
     * @return WebvttStylePassthrough::*|null
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
            if (!WebvttAccessibilitySubs::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "accessibility" for "%s". The value "%s" is not a valid "WebvttAccessibilitySubs".', __CLASS__, $v));
            }
            $payload['accessibility'] = $v;
        }
        if (null !== $v = $this->stylePassthrough) {
            if (!WebvttStylePassthrough::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "stylePassthrough" for "%s". The value "%s" is not a valid "WebvttStylePassthrough".', __CLASS__, $v));
            }
            $payload['stylePassthrough'] = $v;
        }

        return $payload;
    }
}
