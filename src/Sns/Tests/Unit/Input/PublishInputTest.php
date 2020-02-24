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
        $input = new PublishInput([
            'TopicArn' => 'arn:aws:sns:us-east-1:46563727:async',
            'Message' => 'Foo',
        ]);

        $expected = '
            Action=Publish
            &Version=2010-03-31
            &TopicArn=arn%3Aaws%3Asns%3Aus-east-1%3A46563727%3Aasync
            &Message=Foo
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->requestBody());
    }
}
