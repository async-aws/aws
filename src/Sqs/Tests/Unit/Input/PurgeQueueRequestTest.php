<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\PurgeQueueRequest;
use PHPUnit\Framework\TestCase;

class PurgeQueueRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new PurgeQueueRequest([
            'QueueUrl' => 'change me',
        ]);

        $expected = trim('
        Action=PurgeQueue
        &Version=2012-11-05
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
