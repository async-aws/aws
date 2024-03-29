<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The range of possible sequence numbers for the shard.
 */
final class SequenceNumberRange
{
    /**
     * The starting sequence number for the range.
     *
     * @var string
     */
    private $startingSequenceNumber;

    /**
     * The ending sequence number for the range. Shards that are in the OPEN state have an ending sequence number of `null`.
     *
     * @var string|null
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
        $this->startingSequenceNumber = $input['StartingSequenceNumber'] ?? $this->throwException(new InvalidArgument('Missing required field "StartingSequenceNumber".'));
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
