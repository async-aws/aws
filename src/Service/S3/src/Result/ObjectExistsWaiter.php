<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Response;
use AsyncAws\Core\Waiter;
use AsyncAws\S3\Input\HeadObjectRequest;
use AsyncAws\S3\S3Client;

class ObjectExistsWaiter extends Waiter
{
    protected const WAIT_TIMEOUT = 100.0;
    protected const WAIT_DELAY = 5.0;

    protected function extractState(Response $response, ?HttpException $exception): string
    {
        if (200 === $response->getStatusCode()) {
            return self::STATE_SUCCESS;
        }

        if (404 === $response->getStatusCode()) {
            return self::STATE_PENDING;
        }

        /** @psalm-suppress TypeDoesNotContainType */
        return null === $exception ? self::STATE_PENDING : self::STATE_FAILURE;
    }

    protected function refreshState(): Waiter
    {
        if (!$this->awsClient instanceof S3Client) {
            throw new \InvalidArgumentException('missing client injected in waiter result');
        }
        if (!$this->input instanceof HeadObjectRequest) {
            throw new \InvalidArgumentException('missing last request injected in waiter result');
        }

        return $this->awsClient->ObjectExists($this->input);
    }
}
