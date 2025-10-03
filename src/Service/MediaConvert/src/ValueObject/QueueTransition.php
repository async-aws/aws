<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Description of the source and destination queues between which the job has moved, along with the timestamp of the
 * move.
 */
final class QueueTransition
{
    /**
     * The queue that the job was on after the transition.
     *
     * @var string|null
     */
    private $destinationQueue;

    /**
     * The queue that the job was on before the transition.
     *
     * @var string|null
     */
    private $sourceQueue;

    /**
     * The time, in Unix epoch format, that the job moved from the source queue to the destination queue.
     *
     * @var \DateTimeImmutable|null
     */
    private $timestamp;

    /**
     * @param array{
     *   DestinationQueue?: string|null,
     *   SourceQueue?: string|null,
     *   Timestamp?: \DateTimeImmutable|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->destinationQueue = $input['DestinationQueue'] ?? null;
        $this->sourceQueue = $input['SourceQueue'] ?? null;
        $this->timestamp = $input['Timestamp'] ?? null;
    }

    /**
     * @param array{
     *   DestinationQueue?: string|null,
     *   SourceQueue?: string|null,
     *   Timestamp?: \DateTimeImmutable|null,
     * }|QueueTransition $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDestinationQueue(): ?string
    {
        return $this->destinationQueue;
    }

    public function getSourceQueue(): ?string
    {
        return $this->sourceQueue;
    }

    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }
}
