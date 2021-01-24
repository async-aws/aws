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
     */
    private $QueueUrl;

    public function getQueueUrl(): ?string
    {
        $this->initialize();

        return $this->QueueUrl;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->CreateQueueResult;

        $this->QueueUrl = ($v = $data->QueueUrl) ? (string) $v : null;
    }
}
