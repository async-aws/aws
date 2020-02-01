<?php

namespace AsyncAws\Sqs;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Sqs\Input\SendMessageRequest;
use AsyncAws\Sqs\Result\GetQueueUrlResult;
use AsyncAws\Sqs\Result\SendMessageResult;

class SqsClient extends AbstractApi
{
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
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new SendMessageResult($response);
    }
}
