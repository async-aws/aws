<?php

namespace AsyncAws\Kinesis\ValueObject;

/**
 * The range of possible sequence numbers for the shard.
 */
final class SequenceNumberRange
{
    /**
     * The starting sequence number for the range.
     */
    private $startingSequenceNumber;

    /**
     * The ending sequence number for the range. Shards that are in the OPEN state have an ending sequence number of `null`.
     */
    private $endingSequenceNumber;

    /**
     * @param array{
     *   StartingSequenceNumber: string,
     *   EndingSequenceNumber?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->startingSequenceNumber = $input['StartingSequenceNumber'] ?? null;
        $this->endingSequenceNumber = $input['EndingSequenceNumber'] ?? null;
    }

    /**
     * @param array{
     *   StartingSequenceNumber: string,
     *   EndingSequenceNumber?: null|string,
     * }|SequenceNumberRange $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndingSequenceNumber(): ?string
    {
        return $this->endingSequenceNumber;
    }

    public function getStartingSequenceNumber(): string
    {
        return $this->startingSequenceNumber;
    }
}
