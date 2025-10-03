<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Specify one or more Transitions for your video overlay. Use Transitions to reposition or resize your overlay over
 * time. To use the same position and size for the duration of your video overlay: Leave blank. To specify a Transition:
 * Enter a value for Start timecode, End Timecode, X Position, Y Position, Width, or Height.
 */
final class VideoOverlayTransition
{
    /**
     * Specify the ending position for this transition, relative to the base input video's frame. Your video overlay will
     * move smoothly to this position, beginning at this transition's Start timecode and ending at this transition's End
     * timecode.
     *
     * @var VideoOverlayPosition|null
     */
    private $endPosition;

    /**
     * Specify the timecode for when this transition ends. Use the format HH:MM:SS:FF or HH:MM:SS;FF, where HH is the hour,
     * MM is the minute, SS is the second, and FF is the frame number. When entering this value, take into account your
     * choice for Timecode source.
     *
     * @var string|null
     */
    private $endTimecode;

    /**
     * Specify the timecode for when this transition begins. Use the format HH:MM:SS:FF or HH:MM:SS;FF, where HH is the
     * hour, MM is the minute, SS is the second, and FF is the frame number. When entering this value, take into account
     * your choice for Timecode source.
     *
     * @var string|null
     */
    private $startTimecode;

    /**
     * @param array{
     *   EndPosition?: VideoOverlayPosition|array|null,
     *   EndTimecode?: string|null,
     *   StartTimecode?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->endPosition = isset($input['EndPosition']) ? VideoOverlayPosition::create($input['EndPosition']) : null;
        $this->endTimecode = $input['EndTimecode'] ?? null;
        $this->startTimecode = $input['StartTimecode'] ?? null;
    }

    /**
     * @param array{
     *   EndPosition?: VideoOverlayPosition|array|null,
     *   EndTimecode?: string|null,
     *   StartTimecode?: string|null,
     * }|VideoOverlayTransition $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndPosition(): ?VideoOverlayPosition
    {
        return $this->endPosition;
    }

    public function getEndTimecode(): ?string
    {
        return $this->endTimecode;
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
        if (null !== $v = $this->endPosition) {
            $payload['endPosition'] = $v->requestBody();
        }
        if (null !== $v = $this->endTimecode) {
            $payload['endTimecode'] = $v;
        }
        if (null !== $v = $this->startTimecode) {
            $payload['startTimecode'] = $v;
        }

        return $payload;
    }
}
