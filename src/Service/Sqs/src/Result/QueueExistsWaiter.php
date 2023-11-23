<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Waiter;
use AsyncAws\Sqs\Input\GetQueueUrlRequest;
use AsyncAws\Sqs\SqsClient;

class QueueExistsWaiter extends Waiter
{
    protected const WAIT_TIMEOUT = 200.0;
    protected const WAIT_DELAY = 5.0;

    protected function extractState(Response $response, ?HttpException $exception): string
    {
        if (200 === $response->getStatusCode()) {
            return self::STATE_SUCCESS;
        }

        if (null !== $exception && 'QueueDoesNotExist' === $exception->getAwsCode()) {
            return self::STATE_PENDING;
        }

        return null === $exception ? self::STATE_PENDING : self::STATE_FAILURE;
    }

    protected function refreshState(): Waiter
    {
        if (!$this->awsClient instanceof SqsClient) {
            throw new InvalidArgument('missing client injected in waiter result');
        }
        if (!$this->input instanceof GetQueueUrlRequest) {
            throw new InvalidArgument('missing last request injected in waiter result');
        }

        return $this->awsClient->queueExists($this->input);
    }
}
