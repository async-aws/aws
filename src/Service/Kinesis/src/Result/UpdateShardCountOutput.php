<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class UpdateShardCountOutput extends Result
{
    /**
     * The name of the stream.
     */
    private $streamName;

    /**
     * The current number of shards.
     */
    private $currentShardCount;

    /**
     * The updated number of shards.
     */
    private $targetShardCount;

    /**
     * The ARN of the stream.
     */
    private $streamArn;

    public function getCurrentShardCount(): ?int
    {
        $this->initialize();

        return $this->currentShardCount;
    }

    public function getStreamArn(): ?string
    {
        $this->initialize();

        return $this->streamArn;
    }

    public function getStreamName(): ?string
    {
        $this->initialize();

        return $this->streamName;
    }

    public function getTargetShardCount(): ?int
    {
        $this->initialize();

        return $this->targetShardCount;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->streamName = isset($data['StreamName']) ? (string) $data['StreamName'] : null;
        $this->currentShardCount = isset($data['CurrentShardCount']) ? (int) $data['CurrentShardCount'] : null;
        $this->targetShardCount = isset($data['TargetShardCount']) ? (int) $data['TargetShardCount'] : null;
        $this->streamArn = isset($data['StreamARN']) ? (string) $data['StreamARN'] : null;
    }
}
