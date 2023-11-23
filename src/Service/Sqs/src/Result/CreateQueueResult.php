<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Returns the `QueueUrl` attribute of the created queue.
 */
class CreateQueueResult extends Result
{
    /**
     * The URL of the created Amazon SQS queue.
     *
     * @var string|null
     */
    private $queueUrl;

    public function getQueueUrl(): ?string
    {
        $this->initialize();

        return $this->queueUrl;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->queueUrl = isset($data['QueueUrl']) ? (string) $data['QueueUrl'] : null;
    }
}
