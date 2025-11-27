<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\TtmlStylePassthrough;

/**
 * Settings related to TTML captions. TTML is a sidecar format that holds captions in a file that is separate from the
 * video container. Set up sidecar captions in the same output group, but different output from your video. For more
 * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/ttml-and-webvtt-output-captions.html.
 */
final class TtmlDestinationSettings
{
    /**
     * Pass through style and position information from a TTML-like input source (TTML, IMSC, SMPTE-TT) to the TTML output.
     *
     * @var TtmlStylePassthrough::*|null
     */
    private $stylePassthrough;

    /**
     * @param array{
     *   StylePassthrough?: TtmlStylePassthrough::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->stylePassthrough = $input['StylePassthrough'] ?? null;
    }

    /**
     * @param array{
     *   StylePassthrough?: TtmlStylePassthrough::*|null,
     * }|TtmlDestinationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return TtmlStylePassthrough::*|null
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
            if (!TtmlStylePassthrough::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "stylePassthrough" for "%s". The value "%s" is not a valid "TtmlStylePassthrough".', __CLASS__, $v));
            }
            $payload['stylePassthrough'] = $v;
        }

        return $payload;
    }
}
