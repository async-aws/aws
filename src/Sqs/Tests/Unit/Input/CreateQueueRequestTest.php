<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\CreateQueueRequest;
use PHPUnit\Framework\TestCase;

class CreateQueueRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new CreateQueueRequest([
            'QueueName' => 'change me',
            'Attributes' => ['change me' => 'change me'],
            'tags' => ['change me' => 'change me'],
        ]);

        $expected = trim('
        Action=CreateQueue
        &Version=2012-11-05
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
