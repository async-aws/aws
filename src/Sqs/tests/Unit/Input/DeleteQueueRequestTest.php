<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\DeleteQueueRequest;

class DeleteQueueRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new DeleteQueueRequest([
            'QueueUrl' => 'queueUrl',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteQueue.html */
        $expected = '
Action=DeleteQueue
&Version=2012-11-05
&QueueUrl=queueUrl
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->requestBody());
    }
}
