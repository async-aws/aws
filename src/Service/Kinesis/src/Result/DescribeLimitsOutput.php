<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DescribeLimitsOutput extends Result
{
    /**
     * The maximum number of shards.
     */
    private $shardLimit;

    /**
     * The number of open shards.
     */
    private $openShardCount;

    public function getOpenShardCount(): int
    {
        $this->initialize();

        return $this->openShardCount;
    }

    public function getShardLimit(): int
    {
        $this->initialize();

        return $this->shardLimit;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->shardLimit = (int) $data['ShardLimit'];
        $this->openShardCount = (int) $data['OpenShardCount'];
    }
}
