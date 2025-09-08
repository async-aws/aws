<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\SrtStylePassthrough;

/**
 * Settings related to SRT captions. SRT is a sidecar format that holds captions in a file that is separate from the
 * video container. Set up sidecar captions in the same output group, but different output from your video.
 */
final class SrtDestinationSettings
{
    /**
     * Set Style passthrough to ENABLED to use the available style, color, and position information from your input
     * captions. MediaConvert uses default settings for any missing style and position information in your input captions.
     * Set Style passthrough to DISABLED, or leave blank, to ignore the style and position information from your input
     * captions and use simplified output captions.
     *
     * @var SrtStylePassthrough::*|null
     */
    private $stylePassthrough;

    /**
     * @param array{
     *   StylePassthrough?: SrtStylePassthrough::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->stylePassthrough = $input['StylePassthrough'] ?? null;
    }

    /**
     * @param array{
     *   StylePassthrough?: SrtStylePassthrough::*|null,
     * }|SrtDestinationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return SrtStylePassthrough::*|null
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
        if (null !== $v = $this->stylePassthrough) {
            if (!SrtStylePassthrough::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "stylePassthrough" for "%s". The value "%s" is not a valid "SrtStylePassthrough".', __CLASS__, $v));
            }
            $payload['stylePassthrough'] = $v;
        }

        return $payload;
    }
}
