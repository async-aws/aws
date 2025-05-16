<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\VideoOverlayPlayBackMode;

/**
 * Overlay one or more videos on top of your input video. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/video-overlays.html.
 */
final class VideoOverlay
{
    /**
     * Specify a rectangle of content to crop and use from your video overlay's input video. When you do, MediaConvert uses
     * the cropped dimensions that you specify under X offset, Y offset, Width, and Height.
     *
     * @var VideoOverlayCrop|null
     */
    private $crop;

    /**
     * Enter the end timecode in the base input video for this overlay. Your overlay will be active through this frame. To
     * display your video overlay for the duration of the base input video: Leave blank. Use the format HH:MM:SS:FF or
     * HH:MM:SS;FF, where HH is the hour, MM is the minute, SS isthe second, and FF is the frame number. When entering this
     * value, take into account your choice for the base input video's timecode source. For example, if you have embedded
     * timecodes that start at 01:00:00:00 and you want your overlay to end ten minutes into the video, enter 01:10:00:00.
     *
     * @var string|null
     */
    private $endTimecode;

    /**
     * Specify the Initial position of your video overlay. To specify the Initial position of your video overlay, including
     * distance from the left or top edge of the base input video's frame, or size: Enter a value for X position, Y
     * position, Width, or Height. To use the full frame of the base input video: Leave blank.
     *
     * @var VideoOverlayPosition|null
     */
    private $initialPosition;

    /**
     * Input settings for Video overlay. You can include one or more video overlays in sequence at different times that you
     * specify.
     *
     * @var VideoOverlayInput|null
     */
    private $input;

    /**
     * Specify whether your video overlay repeats or plays only once. To repeat your video overlay on a loop: Keep the
     * default value, Repeat. Your overlay will repeat for the duration of the base input video. To playback your video
     * overlay only once: Choose Once. With either option, you can end playback at a time that you specify by entering a
     * value for End timecode.
     *
     * @var VideoOverlayPlayBackMode::*|null
     */
    private $playback;

    /**
     * Enter the start timecode in the base input video for this overlay. Your overlay will be active starting with this
     * frame. To display your video overlay starting at the beginning of the base input video: Leave blank. Use the format
     * HH:MM:SS:FF or HH:MM:SS;FF, where HH is the hour, MM is the minute, SS is the second, and FF is the frame number.
     * When entering this value, take into account your choice for the base input video's timecode source. For example, if
     * you have embedded timecodes that start at 01:00:00:00 and you want your overlay to begin five minutes into the video,
     * enter 01:05:00:00.
     *
     * @var string|null
     */
    private $startTimecode;

    /**
     * Specify one or more transitions for your video overlay. Use Transitions to reposition or resize your overlay over
     * time. To use the same position and size for the duration of your video overlay: Leave blank. To specify a Transition:
     * Enter a value for Start timecode, End Timecode, X Position, Y Position, Width, or Height.
     *
     * @var VideoOverlayTransition[]|null
     */
    private $transitions;

    /**
     * @param array{
     *   Crop?: null|VideoOverlayCrop|array,
     *   EndTimecode?: null|string,
     *   InitialPosition?: null|VideoOverlayPosition|array,
     *   Input?: null|VideoOverlayInput|array,
     *   Playback?: null|VideoOverlayPlayBackMode::*,
     *   StartTimecode?: null|string,
     *   Transitions?: null|array<VideoOverlayTransition|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->crop = isset($input['Crop']) ? VideoOverlayCrop::create($input['Crop']) : null;
        $this->endTimecode = $input['EndTimecode'] ?? null;
        $this->initialPosition = isset($input['InitialPosition']) ? VideoOverlayPosition::create($input['InitialPosition']) : null;
        $this->input = isset($input['Input']) ? VideoOverlayInput::create($input['Input']) : null;
        $this->playback = $input['Playback'] ?? null;
        $this->startTimecode = $input['StartTimecode'] ?? null;
        $this->transitions = isset($input['Transitions']) ? array_map([VideoOverlayTransition::class, 'create'], $input['Transitions']) : null;
    }

    /**
     * @param array{
     *   Crop?: null|VideoOverlayCrop|array,
     *   EndTimecode?: null|string,
     *   InitialPosition?: null|VideoOverlayPosition|array,
     *   Input?: null|VideoOverlayInput|array,
     *   Playback?: null|VideoOverlayPlayBackMode::*,
     *   StartTimecode?: null|string,
     *   Transitions?: null|array<VideoOverlayTransition|array>,
     * }|VideoOverlay $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCrop(): ?VideoOverlayCrop
    {
        return $this->crop;
    }

    public function getEndTimecode(): ?string
    {
        return $this->endTimecode;
    }

    public function getInitialPosition(): ?VideoOverlayPosition
    {
        return $this->initialPosition;
    }

    public function getInput(): ?VideoOverlayInput
    {
        return $this->input;
    }

    /**
     * @return VideoOverlayPlayBackMode::*|null
     */
    public function getPlayback(): ?string
    {
        return $this->playback;
    }

    public function getStartTimecode(): ?string
    {
        return $this->startTimecode;
    }

    /**
     * @return VideoOverlayTransition[]
     */
    public function getTransitions(): array
    {
        return $this->transitions ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->crop) {
            $payload['crop'] = $v->requestBody();
        }
        if (null !== $v = $this->endTimecode) {
            $payload['endTimecode'] = $v;
        }
        if (null !== $v = $this->initialPosition) {
            $payload['initialPosition'] = $v->requestBody();
        }
        if (null !== $v = $this->input) {
            $payload['input'] = $v->requestBody();
        }
        if (null !== $v = $this->playback) {
            if (!VideoOverlayPlayBackMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "playback" for "%s". The value "%s" is not a valid "VideoOverlayPlayBackMode".', __CLASS__, $v));
            }
            $payload['playback'] = $v;
        }
        if (null !== $v = $this->startTimecode) {
            $payload['startTimecode'] = $v;
        }
        if (null !== $v = $this->transitions) {
            $index = -1;
            $payload['transitions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['transitions'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
