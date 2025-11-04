<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\VideoSelectorMode;

/**
 * Optional settings when you set Codec to the value Passthrough.
 */
final class PassthroughSettings
{
    /**
     * AUTO will select the highest bitrate input in the video selector source. REMUX_ALL will passthrough all the selected
     * streams in the video selector source. When selecting streams from multiple renditions (i.e. using Stream video
     * selector type): REMUX_ALL will only remux all streams selected, and AUTO will use the highest bitrate video stream
     * among the selected streams as source.
     *
     * @var VideoSelectorMode::*|null
     */
    private $videoSelectorMode;

    /**
     * @param array{
     *   VideoSelectorMode?: VideoSelectorMode::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->videoSelectorMode = $input['VideoSelectorMode'] ?? null;
    }

    /**
     * @param array{
     *   VideoSelectorMode?: VideoSelectorMode::*|null,
     * }|PassthroughSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return VideoSelectorMode::*|null
     */
    public function getVideoSelectorMode(): ?string
    {
        return $this->videoSelectorMode;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->videoSelectorMode) {
            if (!VideoSelectorMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "videoSelectorMode" for "%s". The value "%s" is not a valid "VideoSelectorMode".', __CLASS__, $v));
            }
            $payload['videoSelectorMode'] = $v;
        }

        return $payload;
    }
}
