<?php

namespace AsyncAws\Kinesis\ValueObject;

/**
 * The range of possible hash key values for the shard, which is a set of ordered contiguous positive integers.
 */
final class HashKeyRange
{
    /**
     * The starting hash key of the hash key range.
     */
    private $startingHashKey;

    /**
     * The ending hash key of the hash key range.
     */
    private $endingHashKey;

    /**
     * @param array{
     *   StartingHashKey: string,
     *   EndingHashKey: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->startingHashKey = $input['StartingHashKey'] ?? null;
        $this->endingHashKey = $input['EndingHashKey'] ?? null;
    }

    /**
     * @param array{
     *   StartingHashKey: string,
     *   EndingHashKey: string,
     * }|HashKeyRange $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndingHashKey(): string
    {
        return $this->endingHashKey;
    }

    public function getStartingHashKey(): string
    {
        return $this->startingHashKey;
    }
}
