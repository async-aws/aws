<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\DeleteMessageRequest;

class DeleteMessageRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteMessageRequest([
            'QueueUrl' => 'queueUrl',
            'ReceiptHandle' => 'MbZj6wDWli+JvwwJaBV+3dcjk2YW2vA3+STFFljT',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteMessage.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: AmazonSQS.DeleteMessage

            {
                "QueueUrl": "queueUrl",
                "ReceiptHandle": "MbZj6wDWli+JvwwJaBV+3dcjk2YW2vA3+STFFljT"
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
