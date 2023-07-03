<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DescribeLimitsOutput extends Result
{
    /**
     * The maximum number of shards.
     *
     * @var int
     */
    private $shardLimit;

    /**
     * The number of open shards.
     *
     * @var int
     */
    private $openShardCount;

    /**
     * Indicates the number of data streams with the on-demand capacity mode.
     *
     * @var int
     */
    private $onDemandStreamCount;

    /**
     * The maximum number of data streams with the on-demand capacity mode.
     *
     * @var int
     */
    private $onDemandStreamCountLimit;

    public function getOnDemandStreamCount(): int
    {
        $this->initialize();

        return $this->onDemandStreamCount;
    }

    public function getOnDemandStreamCountLimit(): int
    {
        $this->initialize();

        return $this->onDemandStreamCountLimit;
    }

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
        $this->onDemandStreamCount = (int) $data['OnDemandStreamCount'];
        $this->onDemandStreamCountLimit = (int) $data['OnDemandStreamCountLimit'];
    }
}
