<?php

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\MessageAttributeValue;
use AsyncAws\Sns\Input\PublishInput;

class PublishInputTest extends TestCase
{
    public function testBody()
    {
        $input = PublishInput::create(['Message' => 'foobar']);
        $body = $input->requestBody();

        self::assertStringContainsString('Message=foobar', $body);
    }

    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new PublishInput([
            'TopicArn' => 'change me',
            'TargetArn' => 'change me',
            'PhoneNumber' => 'change me',
            'Message' => 'change me',
            'Subject' => 'change me',
            'MessageStructure' => 'change me',
            'MessageAttributes' => ['change me' => new MessageAttributeValue([
                'DataType' => 'change me',
                'StringValue' => 'change me',
                'BinaryValue' => 'change me',
            ])],
        ]);

        $expected = '
            Action=Publish
            &Version=2010-03-31
            &ChangeIt=Change+it
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->requestBody());
    }
}
