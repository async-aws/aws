<?php

declare(strict_types=1);

namespace AsyncAws\Sqs;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Sqs\Result\GetQueueUrlResult;
use AsyncAws\Sqs\Result\SendMessageResult;

class SqsClient extends AbstractApi
{
    public function sendMessage(array $body): SendMessageResult
    {
        $response = $this->getResponse('POST', $body);

        return new SendMessageResult($response);
    }

    public function getQueueUrl(array $body): GetQueueUrlResult
    {
        $body['Action'] = 'GetQueueUrl';
        $response = $this->getResponse('POST', $body);

        return new GetQueueUrlResult($response);
    }

    protected function getServiceCode(): string
    {
        return 'sqs';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
