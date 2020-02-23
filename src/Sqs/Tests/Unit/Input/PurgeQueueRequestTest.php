<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\PurgeQueueRequest;
use PHPUnit\Framework\TestCase;

class PurgeQueueRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new PurgeQueueRequest([
            'QueueUrl' => 'queueUrl',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_PurgeQueue.html */
        $expected = trim('
Action=PurgeQueue
&Version=2012-11-05
&QueueUrl=queueUrl
        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
