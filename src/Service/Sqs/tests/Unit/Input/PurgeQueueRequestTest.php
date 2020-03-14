<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\PurgeQueueRequest;

class PurgeQueueRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new PurgeQueueRequest([
            'QueueUrl' => 'queueUrl',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_PurgeQueue.html */
        $expected = '
Action=PurgeQueue
&Version=2012-11-05
&QueueUrl=queueUrl
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->request()->getBody()->stringify());
    }
}
