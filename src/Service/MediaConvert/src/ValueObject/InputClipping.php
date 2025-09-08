<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * To transcode only portions of your input, include one input clip for each part of your input that you want in your
 * output. All input clips that you specify will be included in every output of the job. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/assembling-multiple-inputs-and-input-clips.html.
 */
final class InputClipping
{
    /**
     * Set End timecode to the end of the portion of the input you are clipping. The frame corresponding to the End timecode
     * value is included in the clip. Start timecode or End timecode may be left blank, but not both. Use the format
     * HH:MM:SS:FF or HH:MM:SS;FF, where HH is the hour, MM is the minute, SS is the second, and FF is the frame number.
     * When choosing this value, take into account your setting for timecode source under input settings. For example, if
     * you have embedded timecodes that start at 01:00:00:00 and you want your clip to end six minutes into the video, use
     * 01:06:00:00.
     *
     * @var string|null
     */
    private $endTimecode;

    /**
     * Set Start timecode to the beginning of the portion of the input you are clipping. The frame corresponding to the
     * Start timecode value is included in the clip. Start timecode or End timecode may be left blank, but not both. Use the
     * format HH:MM:SS:FF or HH:MM:SS;FF, where HH is the hour, MM is the minute, SS is the second, and FF is the frame
     * number. When choosing this value, take into account your setting for Input timecode source. For example, if you have
     * embedded timecodes that start at 01:00:00:00 and you want your clip to begin five minutes into the video, use
     * 01:05:00:00.
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
     * }|InputClipping $input
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
