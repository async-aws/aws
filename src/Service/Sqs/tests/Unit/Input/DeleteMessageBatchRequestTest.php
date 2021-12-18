<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\DeleteMessageBatchRequest;
use AsyncAws\Sqs\ValueObject\DeleteMessageBatchRequestEntry;

class DeleteMessageBatchRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteMessageBatchRequest([
            'QueueUrl' => 'queueUrl',
            'Entries' => [new DeleteMessageBatchRequestEntry([
                'Id' => 'qwertyuiop',
                'ReceiptHandle' => 'MbZj6wDWli+JvwwJaBV+3dcjk2YW2vA3+STFFljT',
            ])],
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteMessageBatch.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DeleteMessageBatch
            &DeleteMessageBatchRequestEntry.1.Id=qwertyuiop
            &DeleteMessageBatchRequestEntry.1.ReceiptHandle=MbZj6wDWli%2BJvwwJaBV%2B3dcjk2YW2vA3%2BSTFFljT
            &QueueUrl=queueUrl
            &Version=2012-11-05
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
