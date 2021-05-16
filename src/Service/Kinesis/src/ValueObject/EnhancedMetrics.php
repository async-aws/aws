<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Kinesis\Enum\MetricsName;

/**
 * Represents enhanced metrics types.
 */
final class EnhancedMetrics
{
    /**
     * List of shard-level metrics.
     */
    private $shardLevelMetrics;

    /**
     * @param array{
     *   ShardLevelMetrics?: null|list<MetricsName::*>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->shardLevelMetrics = $input['ShardLevelMetrics'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<MetricsName::*>
     */
    public function getShardLevelMetrics(): array
    {
        return $this->shardLevelMetrics ?? [];
    }
}
