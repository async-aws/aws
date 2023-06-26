<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the output for `GetShardIterator`.
 */
class GetShardIteratorOutput extends Result
{
    /**
     * The position in the shard from which to start reading data records sequentially. A shard iterator specifies this
     * position using the sequence number of a data record in a shard.
     *
     * @var string|null
     */
    private $shardIterator;

    public function getShardIterator(): ?string
    {
        $this->initialize();

        return $this->shardIterator;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->shardIterator = isset($data['ShardIterator']) ? (string) $data['ShardIterator'] : null;
    }
}
