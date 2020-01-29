<?php

declare(strict_types=1);

namespace WorkingTitle\Aws\Sqs;

use WorkingTitle\Aws\AbstractApi;
use WorkingTitle\Aws\ResultPromise;

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
