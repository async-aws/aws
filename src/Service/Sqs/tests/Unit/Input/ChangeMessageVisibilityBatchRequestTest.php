<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\ChangeMessageVisibilityBatchRequest;
use AsyncAws\Sqs\ValueObject\ChangeMessageVisibilityBatchRequestEntry;

class ChangeMessageVisibilityBatchRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ChangeMessageVisibilityBatchRequest([
            'QueueUrl' => 'queueUrl',
            'Entries' => [new ChangeMessageVisibilityBatchRequestEntry([
                'Id' => 'qwertyuiop',
                'ReceiptHandle' => 'MbZj6wDWli+JvwwJaBV+3dcjk2YW2vA3+STFFljT',
                'VisibilityTimeout' => 60,
            ])],
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ChangeMessageVisibilityBatch.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=ChangeMessageVisibilityBatch
            &ChangeMessageVisibilityBatchRequestEntry.1.Id=qwertyuiop
            &ChangeMessageVisibilityBatchRequestEntry.1.ReceiptHandle=MbZj6wDWli%2BJvwwJaBV%2B3dcjk2YW2vA3%2BSTFFljT
            &ChangeMessageVisibilityBatchRequestEntry.1.VisibilityTimeout=60
            &QueueUrl=queueUrl
            &Version=2012-11-05
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
