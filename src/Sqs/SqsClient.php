<?php

declare(strict_types=1);

namespace AsyncAws\Sqs;

use AsyncAws\Aws\AbstractApi;
use AsyncAws\Aws\Result;
use AsyncAws\Aws\Sqs\Result\SendMessageResult;

class SqsClient extends AbstractApi
{
    /**
     * @return Result<SendMessageResult>
     */
    public function sendMessage(array $body): Result
    {
        $response = $this->getResponse('POST', $body);

        return new Result($response, SendMessageResult::class);
    }

    protected function getServiceCode(): string
    {
        return 'sqs';
    }
}
