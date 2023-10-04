<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Overlay one or more videos on top of your input video.
 */
final class VideoOverlay
{
    /**
     * Enter the end timecode in the underlying input video for this overlay. Your overlay will be active through this
     * frame. To display your video overlay for the duration of the underlying video: Leave blank. Use the format
     * HH:MM:SS:FF or HH:MM:SS;FF, where HH is the hour, MM is the minute, SS is the second, and FF is the frame number.
     * When entering this value, take into account your choice for the underlying Input timecode source. For example, if you
     * have embedded timecodes that start at 01:00:00:00 and you want your overlay to end ten minutes into the video, enter
     * 01:10:00:00.
     *
     * @var string|null
     */
    private $endTimecode;

    /**
     * Input settings for Video overlay. You can include one or more video overlays in sequence at different times that you
     * specify.
     *
     * @var VideoOverlayInput|null
     */
    private $input;

    /**
     * Enter the start timecode in the underlying input video for this overlay. Your overlay will be active starting with
     * this frame. To display your video overlay starting at the beginning of the underlying video: Leave blank. Use the
     * format HH:MM:SS:FF or HH:MM:SS;FF, where HH is the hour, MM is the minute, SS is the second, and FF is the frame
     * number. When entering this value, take into account your choice for the underlying Input timecode source. For
     * example, if you have embedded timecodes that start at 01:00:00:00 and you want your overlay to begin five minutes
     * into the video, enter 01:05:00:00.
     *
     * @var string|null
     */
    private $startTimecode;

    /**
     * @param array{
     *   EndTimecode?: null|string,
     *   Input?: null|VideoOverlayInput|array,
     *   StartTimecode?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->endTimecode = $input['EndTimecode'] ?? null;
        $this->input = isset($input['Input']) ? VideoOverlayInput::create($input['Input']) : null;
        $this->startTimecode = $input['StartTimecode'] ?? null;
    }

    /**
     * @param array{
     *   EndTimecode?: null|string,
     *   Input?: null|VideoOverlayInput|array,
     *   StartTimecode?: null|string,
     * }|VideoOverlay $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndTimecode(): ?string
    {
        return $this->endTimecode;
    }

    public function getInput(): ?VideoOverlayInput
    {
        return $this->input;
    }

    public function getStartTimecode(): ?string
    {
        return $this->startTimecode;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->endTimecode) {
            $payload['endTimecode'] = $v;
        }
        if (null !== $v = $this->input) {
            $payload['input'] = $v->requestBody();
        }
        if (null !== $v = $this->startTimecode) {
            $payload['startTimecode'] = $v;
        }

        return $payload;
    }
}
