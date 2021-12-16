<?php

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\PublishBatchInput;
use AsyncAws\Sns\ValueObject\MessageAttributeValue;
use AsyncAws\Sns\ValueObject\PublishBatchRequestEntry;

class PublishBatchInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PublishBatchInput([
            'TopicArn' => 'arn:aws:sns:us-east-1:46563727:async',
            'PublishBatchRequestEntries' => [new PublishBatchRequestEntry([
                'Id' => 'qwertyuiop',
                'Message' => 'Foo',
                'Subject' => 'MySubject',
                'MessageAttributes' => ['myAttribute' => new MessageAttributeValue([
                    'DataType' => 'String',
                    'StringValue' => 'Foobar',
                ])],
            ])],
        ]);

        // see https://docs.aws.amazon.com/sns/latest/api/API_PublishBatch.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=PublishBatch
            &PublishBatchRequestEntries.member.1.Id=qwertyuiop
            &PublishBatchRequestEntries.member.1.Message=Foo
            &PublishBatchRequestEntries.member.1.MessageAttributes.entry.1.Name=myAttribute
            &PublishBatchRequestEntries.member.1.MessageAttributes.entry.1.Value.DataType=String
            &PublishBatchRequestEntries.member.1.MessageAttributes.entry.1.Value.StringValue=Foobar
            &PublishBatchRequestEntries.member.1.Subject=MySubject
            &TopicArn=arn%3Aaws%3Asns%3Aus-east-1%3A46563727%3Aasync
            &Version=2010-03-31
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
