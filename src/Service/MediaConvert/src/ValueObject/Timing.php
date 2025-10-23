<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Information about when jobs are submitted, started, and finished is specified in Unix epoch format in seconds.
 */
final class Timing
{
    /**
     * The time, in Unix epoch format, that the transcoding job finished.
     *
     * @var \DateTimeImmutable|null
     */
    private $finishTime;

    /**
     * The time, in Unix epoch format, that transcoding for the job began.
     *
     * @var \DateTimeImmutable|null
     */
    private $startTime;

    /**
     * The time, in Unix epoch format, that you submitted the job.
     *
     * @var \DateTimeImmutable|null
     */
    private $submitTime;

    /**
     * @param array{
     *   FinishTime?: \DateTimeImmutable|null,
     *   StartTime?: \DateTimeImmutable|null,
     *   SubmitTime?: \DateTimeImmutable|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->finishTime = $input['FinishTime'] ?? null;
        $this->startTime = $input['StartTime'] ?? null;
        $this->submitTime = $input['SubmitTime'] ?? null;
    }

    /**
     * @param array{
     *   FinishTime?: \DateTimeImmutable|null,
     *   StartTime?: \DateTimeImmutable|null,
     *   SubmitTime?: \DateTimeImmutable|null,
     * }|Timing $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFinishTime(): ?\DateTimeImmutable
    {
        return $this->finishTime;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getSubmitTime(): ?\DateTimeImmutable
    {
        return $this->submitTime;
    }
}
