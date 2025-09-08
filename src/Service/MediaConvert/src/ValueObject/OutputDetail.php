<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Details regarding output.
 */
final class OutputDetail
{
    /**
     * Duration in milliseconds.
     *
     * @var int|null
     */
    private $durationInMs;

    /**
     * Contains details about the output's video stream.
     *
     * @var VideoDetail|null
     */
    private $videoDetails;

    /**
     * @param array{
     *   DurationInMs?: int|null,
     *   VideoDetails?: VideoDetail|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->durationInMs = $input['DurationInMs'] ?? null;
        $this->videoDetails = isset($input['VideoDetails']) ? VideoDetail::create($input['VideoDetails']) : null;
    }

    /**
     * @param array{
     *   DurationInMs?: int|null,
     *   VideoDetails?: VideoDetail|array|null,
     * }|OutputDetail $input
     */
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
