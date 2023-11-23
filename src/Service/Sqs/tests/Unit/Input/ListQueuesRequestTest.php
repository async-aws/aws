<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\ListQueuesRequest;

class ListQueuesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListQueuesRequest([
            'QueueNamePrefix' => 'M',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ListQueues.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: AmazonSQS.ListQueues

            {
                "QueueNamePrefix": "M"
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
