<?php

declare(strict_types=1);

namespace AsyncAws\Sqs;

use AsyncAws\Aws\AbstractApi;
use AsyncAws\Aws\Result;
use AsyncAws\Sqs\Result\SendMessageResult;

class SqsClient extends AbstractApi
{
    public function sendMessage(array $body): SendMessageResult
    {
        $response = $this->getResponse('POST', $body);

        return new SendMessageResult($response);
    }

    protected function getServiceCode(): string
    {
        return 'sqs';
    }
}
