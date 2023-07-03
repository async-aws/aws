<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The range of possible hash key values for the shard, which is a set of ordered contiguous positive integers.
 */
final class HashKeyRange
{
    /**
     * The starting hash key of the hash key range.
     *
     * @var string
     */
    private $startingHashKey;

    /**
     * The ending hash key of the hash key range.
     *
     * @var string
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
        $this->startingHashKey = $input['StartingHashKey'] ?? $this->throwException(new InvalidArgument('Missing required field "StartingHashKey".'));
        $this->endingHashKey = $input['EndingHashKey'] ?? $this->throwException(new InvalidArgument('Missing required field "EndingHashKey".'));
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
