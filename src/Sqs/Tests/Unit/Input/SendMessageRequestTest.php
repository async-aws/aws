<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\MessageAttributeValue;
use AsyncAws\Sqs\Input\MessageSystemAttributeValue;
use AsyncAws\Sqs\Input\SendMessageRequest;
use PHPUnit\Framework\TestCase;

class SendMessageRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new SendMessageRequest([
            'QueueUrl' => 'change me',
            'MessageBody' => 'change me',
            'DelaySeconds' => 1337,
            'MessageAttributes' => ['change me' => new MessageAttributeValue([
                'StringValue' => 'change me',
                'BinaryValue' => 'change me',
                'StringListValues' => ['change me'],
                'BinaryListValues' => ['change me'],
                'DataType' => 'change me',
            ])],
            'MessageSystemAttributes' => ['change me' => new MessageSystemAttributeValue([
                'StringValue' => 'change me',
                'BinaryValue' => 'change me',
                'StringListValues' => ['change me'],
                'BinaryListValues' => ['change me'],
                'DataType' => 'change me',
            ])],
            'MessageDeduplicationId' => 'change me',
            'MessageGroupId' => 'change me',
        ]);

        $expected = trim('
        Action=SendMessage
        &Version=2012-11-05
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
