<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\ReceiveMessageRequest;
use PHPUnit\Framework\TestCase;

class ReceiveMessageRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new ReceiveMessageRequest([
            'QueueUrl' => 'change me',
            'AttributeNames' => ['change me'],
            'MessageAttributeNames' => ['change me'],
            'MaxNumberOfMessages' => 1337,
            'VisibilityTimeout' => 1337,
            'WaitTimeSeconds' => 1337,
            'ReceiveRequestAttemptId' => 'change me',
        ]);

        $expected = trim('
        Action=ReceiveMessage
        &Version=2012-11-05
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
