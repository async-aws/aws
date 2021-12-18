<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\SendMessageBatchRequest;
use AsyncAws\Sqs\ValueObject\MessageAttributeValue;
use AsyncAws\Sqs\ValueObject\SendMessageBatchRequestEntry;

class SendMessageBatchRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SendMessageBatchRequest([
            'QueueUrl' => 'queueUrl',
            'Entries' => [new SendMessageBatchRequestEntry([
                'Id' => 'qwertyuiop',
                'MessageBody' => 'This is a test message',
                'DelaySeconds' => 45,
                'MessageAttributes' => ['my_attribute_name_1' => new MessageAttributeValue([
                    'StringValue' => 'my_attribute_value_1',
                    'DataType' => 'String',
                ])],
                'MessageDeduplicationId' => 'abcdef',
                'MessageGroupId' => 'abcdef01',
            ])],
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessageBatch.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=SendMessageBatch
            &QueueUrl=queueUrl
            &SendMessageBatchRequestEntry.1.Id=qwertyuiop
            &SendMessageBatchRequestEntry.1.DelaySeconds=45
            &SendMessageBatchRequestEntry.1.MessageAttribute.1.Value.DataType=String
            &SendMessageBatchRequestEntry.1.MessageAttribute.1.Value.StringValue=my_attribute_value_1
            &SendMessageBatchRequestEntry.1.MessageAttribute.1.Name=my_attribute_name_1
            &SendMessageBatchRequestEntry.1.MessageBody=This+is+a+test+message
            &SendMessageBatchRequestEntry.1.MessageDeduplicationId=abcdef
            &SendMessageBatchRequestEntry.1.MessageGroupId=abcdef01
            &Version=2012-11-05
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
