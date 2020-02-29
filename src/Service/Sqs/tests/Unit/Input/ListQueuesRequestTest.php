<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\ListQueuesRequest;

class ListQueuesRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new ListQueuesRequest([
            'QueueNamePrefix' => 'M',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ListQueues.html */
        $expected = '
Action=ListQueues
&Version=2012-11-05
&QueueNamePrefix=M
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->requestBody());
    }
}
