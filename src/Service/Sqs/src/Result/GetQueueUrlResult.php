<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * For more information, see Interpreting Responses in the *Amazon SQS Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-api-responses.html
 */
class GetQueueUrlResult extends Result
{
    /**
     * The URL of the queue.
     */
    private $queueUrl;

    public function getQueueUrl(): ?string
    {
        $this->initialize();

        return $this->queueUrl;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->GetQueueUrlResult;

        $this->queueUrl = ($v = $data->QueueUrl) ? (string) $v : null;
    }
}
