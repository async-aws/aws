<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * For more information, see Interpreting Responses [^1] in the *Amazon SQS Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-api-responses.html
 */
class GetQueueUrlResult extends Result
{
    /**
     * The URL of the queue.
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
