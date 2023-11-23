<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\GetQueueAttributesRequest;

class GetQueueAttributesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetQueueAttributesRequest([
            'QueueUrl' => 'queueUrl',
            'AttributeNames' => ['VisibilityTimeout', 'DelaySeconds', 'ReceiveMessageWaitTimeSeconds'],
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueAttributes.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: AmazonSQS.GetQueueAttributes

            {
                "QueueUrl": "queueUrl",
                "AttributeNames": ["VisibilityTimeout", "DelaySeconds", "ReceiveMessageWaitTimeSeconds"]
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
