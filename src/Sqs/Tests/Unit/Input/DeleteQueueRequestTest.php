<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\DeleteQueueRequest;
use PHPUnit\Framework\TestCase;

class DeleteQueueRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new DeleteQueueRequest([
            'QueueUrl' => 'change me',
        ]);

        $expected = trim('
        Action=DeleteQueue
        &Version=2012-11-05
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
