<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\DeleteQueueRequest;
use PHPUnit\Framework\TestCase;

class DeleteQueueRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new DeleteQueueRequest([
            'QueueUrl' => 'queueUrl',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteQueue.html */
        $expected = trim('
Action=DeleteQueue
&Version=2012-11-05
&QueueUrl=queueUrl
        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
