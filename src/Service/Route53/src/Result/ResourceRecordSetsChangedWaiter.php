<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Waiter;
use AsyncAws\Route53\Enum\ChangeStatus;
use AsyncAws\Route53\Input\GetChangeRequest;
use AsyncAws\Route53\Route53Client;

class ResourceRecordSetsChangedWaiter extends Waiter
{
    protected const WAIT_TIMEOUT = 1800.0;
    protected const WAIT_DELAY = 30.0;

    protected function extractState(Response $response, ?HttpException $exception): string
    {
        if (200 === $response->getStatusCode()) {
            $data = new \SimpleXMLElement($response->getContent());
            $a = !ChangeStatus::exists((string) $data->ChangeInfo->Status) ? ChangeStatus::UNKNOWN_TO_SDK : (string) $data->ChangeInfo->Status;
            if ('INSYNC' === $a) {
                return self::STATE_SUCCESS;
            }
        }

        return null === $exception ? self::STATE_PENDING : self::STATE_FAILURE;
    }

    protected function refreshState(): Waiter
    {
        if (!$this->awsClient instanceof Route53Client) {
            throw new InvalidArgument('missing client injected in waiter result');
        }
        if (!$this->input instanceof GetChangeRequest) {
            throw new InvalidArgument('missing last request injected in waiter result');
        }

        return $this->awsClient->resourceRecordSetsChanged($this->input);
    }
}
