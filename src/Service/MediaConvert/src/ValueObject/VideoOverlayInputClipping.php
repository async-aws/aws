<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * To transcode only portions of your video overlay, include one input clip for each part of your video overlay that you
 * want in your output.
 */
final class VideoOverlayInputClipping
{
    /**
     * Specify the timecode of the last frame to include in your video overlay's clip. Use the format HH:MM:SS:FF or
     * HH:MM:SS;FF, where HH is the hour, MM is the minute, SS is the second, and FF is the frame number. When entering this
     * value, take into account your choice for Timecode source.
     *
     * @var string|null
     */
    private $endTimecode;

    /**
     * Specify the timecode of the first frame to include in your video overlay's clip. Use the format HH:MM:SS:FF or
     * HH:MM:SS;FF, where HH is the hour, MM is the minute, SS is the second, and FF is the frame number. When entering this
     * value, take into account your choice for Timecode source.
     *
     * @var string|null
     */
    private $startTimecode;

    /**
     * @param array{
     *   EndTimecode?: string|null,
     *   StartTimecode?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->endTimecode = $input['EndTimecode'] ?? null;
        $this->startTimecode = $input['StartTimecode'] ?? null;
    }

    /**
     * @param array{
     *   EndTimecode?: string|null,
     *   StartTimecode?: string|null,
     * }|VideoOverlayInputClipping $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
        if (null !== $v = $this->endTimecode) {
            $payload['endTimecode'] = $v;
        }
        if (null !== $v = $this->startTimecode) {
            $payload['startTimecode'] = $v;
        }

        return $payload;
    }
}
