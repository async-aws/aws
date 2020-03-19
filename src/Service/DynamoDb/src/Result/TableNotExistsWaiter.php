<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Response;
use AsyncAws\Core\Waiter;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\DescribeTableInput;

class TableNotExistsWaiter extends Waiter
{
    protected const WAIT_TIMEOUT = 500.0;
    protected const WAIT_DELAY = 2.0;

    protected function extractState(Response $response, ?HttpException $exception): string
    {
        if (400 === $response->getStatusCode()) {
            // {"__type":"com.amazonaws.dynamodb.v20120810#ResourceNotFoundException","message":"Requested resource not found: Table: errors not found"}
            list(, $errorType) = explode('#', $response->toArray()['__type'] ?? '', 2);
            if ('ResourceNotFoundException' === $errorType) {
                return self::STATE_SUCCESS;
            }
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

        return $this->awsClient->TableNotExists($this->input);
    }
}
