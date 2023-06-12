<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Details regarding output.
 */
final class OutputDetail
{
    /**
     * Duration in milliseconds.
     */
    private $durationInMs;

    /**
     * Contains details about the output's video stream.
     */
    private $videoDetails;

    /**
     * @param array{
     *   DurationInMs?: null|int,
     *   VideoDetails?: null|VideoDetail|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->durationInMs = $input['DurationInMs'] ?? null;
        $this->videoDetails = isset($input['VideoDetails']) ? VideoDetail::create($input['VideoDetails']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDurationInMs(): ?int
    {
        return $this->durationInMs;
    }

    public function getVideoDetails(): ?VideoDetail
    {
        return $this->videoDetails;
    }
}
