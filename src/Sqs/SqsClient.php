<?php

declare(strict_types=1);

namespace WorkingTitle\Sqs;

use WorkingTitle\Aws\AbstractApi;
use WorkingTitle\Aws\ResultPromise;
use WorkingTitle\Aws\Sqs\Result\SendMessageResult;

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
