<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\DeleteQueueRequest;

class DeleteQueueRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteQueueRequest([
            'QueueUrl' => 'queueUrl',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteQueue.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DeleteQueue
            &Version=2012-11-05
            &QueueUrl=queueUrl
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
