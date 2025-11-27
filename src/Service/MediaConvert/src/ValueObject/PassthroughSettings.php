<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\FrameControl;
use AsyncAws\MediaConvert\Enum\VideoSelectorMode;

/**
 * Optional settings when you set Codec to the value Passthrough.
 */
final class PassthroughSettings
{
    /**
     * Choose how MediaConvert handles start and end times for input clipping with video passthrough. Your input video codec
     * must be H.264 or H.265 to use IFRAME. To clip at the nearest IDR-frame: Choose Nearest IDR. If an IDR-frame is not
     * found at the frame that you specify, MediaConvert uses the next compatible IDR-frame. Note that your output may be
     * shorter than your input clip duration. To clip at the nearest I-frame: Choose Nearest I-frame. If an I-frame is not
     * found at the frame that you specify, MediaConvert uses the next compatible I-frame. Note that your output may be
     * shorter than your input clip duration. We only recommend this setting for special workflows, and when you choose this
     * setting your output may not be compatible with most players.
     *
     * @var FrameControl::*|null
     */
    private $frameControl;

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
     *   FrameControl?: FrameControl::*|null,
     *   VideoSelectorMode?: VideoSelectorMode::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->frameControl = $input['FrameControl'] ?? null;
        $this->videoSelectorMode = $input['VideoSelectorMode'] ?? null;
    }

    /**
     * @param array{
     *   FrameControl?: FrameControl::*|null,
     *   VideoSelectorMode?: VideoSelectorMode::*|null,
     * }|PassthroughSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return FrameControl::*|null
     */
    public function getFrameControl(): ?string
    {
        return $this->frameControl;
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
        if (null !== $v = $this->frameControl) {
            if (!FrameControl::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "frameControl" for "%s". The value "%s" is not a valid "FrameControl".', __CLASS__, $v));
            }
            $payload['frameControl'] = $v;
        }
        if (null !== $v = $this->videoSelectorMode) {
            if (!VideoSelectorMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "videoSelectorMode" for "%s". The value "%s" is not a valid "VideoSelectorMode".', __CLASS__, $v));
            }
            $payload['videoSelectorMode'] = $v;
        }

        return $payload;
    }
}
