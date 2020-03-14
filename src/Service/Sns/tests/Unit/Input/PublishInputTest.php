<?php

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\MessageAttributeValue;
use AsyncAws\Sns\Input\PublishInput;

class PublishInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PublishInput([
            'TopicArn' => 'arn:aws:sns:us-east-1:46563727:async',
            'Message' => 'Foo',
            'Subject' => 'MySubject',
            'MessageAttributes' => ['myAttribute' => new MessageAttributeValue([
                'DataType' => 'String',
                'StringValue' => 'Foobar',
            ])],
        ]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=Publish
            &Version=2010-03-31
            &TopicArn=arn%3Aaws%3Asns%3Aus-east-1%3A46563727%3Aasync
            &Message=Foo
            &Subject=MySubject
            &MessageAttributes.entry.1.Name=myAttribute
            &MessageAttributes.entry.1.Value.DataType=String
            &MessageAttributes.entry.1.Value.StringValue=Foobar
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
