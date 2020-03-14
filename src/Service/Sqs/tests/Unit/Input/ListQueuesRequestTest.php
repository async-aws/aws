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
            Content-Type: application/x-www-form-urlencoded

            Action=ListQueues
            &Version=2012-11-05
            &QueueNamePrefix=M
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
