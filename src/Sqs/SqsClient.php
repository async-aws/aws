<?php

namespace AsyncAws\Sqs;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Sqs\Input\SendMessageRequest;
use AsyncAws\Sqs\Result\SendMessageResult;

class SqsClient extends AbstractApi
{
    protected function getServiceCode(): string
    {
        return 'sqs';
    }

    /**
     * @param array{
     *   QueueUrl: string,
     *   MessageBody: string,
     *   DelaySeconds?: int,
     *   MessageAttributes?: array,
     *   MessageSystemAttributes?: array,
     *   MessageDeduplicationId?: string,
     *   MessageGroupId?: string,
     * }|SendMessageRequest $input
     */
    public function sendMessage($input): SendMessageResult
    {
        $input = SendMessageRequest::create($input);

        $response = $this->getResponse('POST', '', $input->requestHeaders(), $this->getEndpoint($input->requestUri(), $input->requestQuery()));

        return new SendMessageResult($response);
    }
}
