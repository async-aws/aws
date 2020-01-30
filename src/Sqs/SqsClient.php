<?php

declare(strict_types=1);

namespace AsyncAws\Sqs;

use AsyncAws\Aws\AbstractApi;
use AsyncAws\Aws\ResultPromise;
use AsyncAws\Aws\Sqs\Result\SendMessageResult;

class SqsClient extends AbstractApi
{
    /**
     * @return ResultPromise<SendMessageResult>
     */
    public function sendMessage(array $body): ResultPromise
    {
        $response = $this->getResponse('POST', $body);

        return new ResultPromise($response, SendMessageResult::class);
    }

    protected function getServiceCode(): string
    {
        return 'sqs';
    }
}
