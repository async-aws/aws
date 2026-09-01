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

    /**
     * The number of channels in the account.
     *
     * @var int|null
     */
    private $channelCount;

    /**
     * The maximum number of channels allowed in the account.
     *
     * @var int|null
     */
    private $channelCountLimit;

    public function getChannelCount(): ?int
    {
        $this->initialize();

        return $this->channelCount;
    }

    public function getChannelCountLimit(): ?int
    {
        $this->initialize();

        return $this->channelCountLimit;
    }

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
        $this->channelCount = isset($data['ChannelCount']) ? (int) $data['ChannelCount'] : null;
        $this->channelCountLimit = isset($data['ChannelCountLimit']) ? (int) $data['ChannelCountLimit'] : null;
    }
}
