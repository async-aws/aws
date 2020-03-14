<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\ChangeMessageVisibilityRequest;

class ChangeMessageVisibilityRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ChangeMessageVisibilityRequest([
            'QueueUrl' => 'queueUrl',
            'ReceiptHandle' => 'MbZj6wDWli+JvwwJaBV+3dcjk2YW2vA3+STFFljT',
            'VisibilityTimeout' => 60,
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ChangeMessageVisibility.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=ChangeMessageVisibility
            &Version=2012-11-05
            &QueueUrl=queueUrl
            &ReceiptHandle=MbZj6wDWli%2BJvwwJaBV%2B3dcjk2YW2vA3%2BSTFFljT
            &VisibilityTimeout=60
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
