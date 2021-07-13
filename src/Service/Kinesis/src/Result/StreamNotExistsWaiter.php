<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Waiter;
use AsyncAws\Kinesis\Input\DescribeStreamInput;
use AsyncAws\Kinesis\KinesisClient;

class StreamNotExistsWaiter extends Waiter
{
    protected const WAIT_TIMEOUT = 180.0;
    protected const WAIT_DELAY = 10.0;

    protected function extractState(Response $response, ?HttpException $exception): string
    {
        if (null !== $exception && 'ResourceNotFoundException' === $exception->getAwsCode()) {
            return self::STATE_SUCCESS;
        }

        /** @psalm-suppress TypeDoesNotContainType */
        return null === $exception ? self::STATE_PENDING : self::STATE_FAILURE;
    }

    protected function refreshState(): Waiter
    {
        if (!$this->awsClient instanceof KinesisClient) {
            throw new InvalidArgument('missing client injected in waiter result');
        }
        if (!$this->input instanceof DescribeStreamInput) {
            throw new InvalidArgument('missing last request injected in waiter result');
        }

        return $this->awsClient->streamNotExists($this->input);
    }
}
