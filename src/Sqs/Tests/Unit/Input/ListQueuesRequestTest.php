<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\ListQueuesRequest;
use PHPUnit\Framework\TestCase;

class ListQueuesRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new ListQueuesRequest([
            'QueueNamePrefix' => 'change me',
        ]);

        $expected = trim('
        Action=ListQueues
        &Version=2012-11-05
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
