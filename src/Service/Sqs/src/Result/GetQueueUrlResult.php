<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * For more information, see Interpreting Responses in the *Amazon Simple Queue Service Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-api-responses.html
 */
class GetQueueUrlResult extends Result
{
    /**
     * The URL of the queue.
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
        $data = $data->GetQueueUrlResult;

        $this->QueueUrl = ($v = $data->QueueUrl) ? (string) $v : null;
    }
}
