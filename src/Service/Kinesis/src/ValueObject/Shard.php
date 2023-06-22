<?php

namespace AsyncAws\Kinesis\ValueObject;

/**
 * A uniquely identified group of data records in a Kinesis data stream.
 */
final class Shard
{
    /**
     * The unique identifier of the shard within the stream.
     */
    private $shardId;

    /**
     * The shard ID of the shard's parent.
     */
    private $parentShardId;

    /**
     * The shard ID of the shard adjacent to the shard's parent.
     */
    private $adjacentParentShardId;

    /**
     * The range of possible hash key values for the shard, which is a set of ordered contiguous positive integers.
     */
    private $hashKeyRange;

    /**
     * The range of possible sequence numbers for the shard.
     */
    private $sequenceNumberRange;

    /**
     * @param array{
     *   ShardId: string,
     *   ParentShardId?: null|string,
     *   AdjacentParentShardId?: null|string,
     *   HashKeyRange: HashKeyRange|array,
     *   SequenceNumberRange: SequenceNumberRange|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->shardId = $input['ShardId'] ?? null;
        $this->parentShardId = $input['ParentShardId'] ?? null;
        $this->adjacentParentShardId = $input['AdjacentParentShardId'] ?? null;
        $this->hashKeyRange = isset($input['HashKeyRange']) ? HashKeyRange::create($input['HashKeyRange']) : null;
        $this->sequenceNumberRange = isset($input['SequenceNumberRange']) ? SequenceNumberRange::create($input['SequenceNumberRange']) : null;
    }

    /**
     * @param array{
     *   ShardId: string,
     *   ParentShardId?: null|string,
     *   AdjacentParentShardId?: null|string,
     *   HashKeyRange: HashKeyRange|array,
     *   SequenceNumberRange: SequenceNumberRange|array,
     * }|Shard $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAdjacentParentShardId(): ?string
    {
        return $this->adjacentParentShardId;
    }

    public function getHashKeyRange(): HashKeyRange
    {
        return $this->hashKeyRange;
    }

    public function getParentShardId(): ?string
    {
        return $this->parentShardId;
    }

    public function getSequenceNumberRange(): SequenceNumberRange
    {
        return $this->sequenceNumberRange;
    }

    public function getShardId(): string
    {
        return $this->shardId;
    }
}
