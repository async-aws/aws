<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\ListQueuesRequest;
use PHPUnit\Framework\TestCase;

class ListQueuesRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new ListQueuesRequest([
            'QueueNamePrefix' => 'M',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ListQueues.html */
        $expected = trim('
Action=ListQueues
&Version=2012-11-05
&QueueNamePrefix=M
        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
