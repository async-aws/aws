<?php

namespace AsyncAws\Kinesis\ValueObject;

/**
 * Output parameter of the GetRecords API. The existing child shard of the current shard.
 */
final class ChildShard
{
    /**
     * The shard ID of the existing child shard of the current shard.
     */
    private $shardId;

    /**
     * The current shard that is the parent of the existing child shard.
     */
    private $parentShards;

    private $hashKeyRange;

    /**
     * @param array{
     *   ShardId: string,
     *   ParentShards: string[],
     *   HashKeyRange: HashKeyRange|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->shardId = $input['ShardId'] ?? null;
        $this->parentShards = $input['ParentShards'] ?? null;
        $this->hashKeyRange = isset($input['HashKeyRange']) ? HashKeyRange::create($input['HashKeyRange']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHashKeyRange(): HashKeyRange
    {
        return $this->hashKeyRange;
    }

    /**
     * @return string[]
     */
    public function getParentShards(): array
    {
        return $this->parentShards ?? [];
    }

    public function getShardId(): string
    {
        return $this->shardId;
    }
}
