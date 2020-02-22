<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\DeleteMessageRequest;
use PHPUnit\Framework\TestCase;

class DeleteMessageRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new DeleteMessageRequest([
            'QueueUrl' => 'change me',
            'ReceiptHandle' => 'change me',
        ]);

        $expected = trim('
        Action=DeleteMessage
        &Version=2012-11-05
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
