<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Response;
use AsyncAws\Core\Waiter;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\DescribeTableInput;

class TableExistsWaiter extends Waiter
{
    protected const WAIT_TIMEOUT = 500.0;
    protected const WAIT_DELAY = 20.0;

    protected function extractState(Response $response, ?HttpException $exception): string
    {
        if (200 === $response->getStatusCode() && 'ACTIVE' === ($response->toArray()['Table']['TableStatus'] ?? null)) {
            return self::STATE_SUCCESS;
        }

        if (null !== $exception) {
            return self::STATE_PENDING;
        }

        /** @psalm-suppress TypeDoesNotContainType */
        return null === $exception ? self::STATE_PENDING : self::STATE_FAILURE;
    }

    protected function refreshState(): Waiter
    {
        if (!$this->awsClient instanceof DynamoDbClient) {
            throw new \InvalidArgumentException('missing client injected in waiter result');
        }
        if (!$this->input instanceof DescribeTableInput) {
            throw new \InvalidArgumentException('missing last request injected in waiter result');
        }

        return $this->awsClient->TableExists($this->input);
    }
}
