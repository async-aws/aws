<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Enum\MessageSystemAttributeNameForSends;
use AsyncAws\Sqs\Input\SendMessageRequest;
use AsyncAws\Sqs\ValueObject\MessageAttributeValue;
use AsyncAws\Sqs\ValueObject\MessageSystemAttributeValue;

class SendMessageRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SendMessageRequest([
            'QueueUrl' => 'queueUrl',
            'MessageBody' => 'This is a test message',
            'DelaySeconds' => 45,
            'MessageAttributes' => ['my_attribute_name_1' => new MessageAttributeValue([
                'StringValue' => 'my_attribute_value_1',
                'DataType' => 'String',
            ])],
            'MessageSystemAttributes' => [MessageSystemAttributeNameForSends::AWSTRACE_HEADER => new MessageSystemAttributeValue([
                'StringListValues' => ['my_attribute_value_2', 'my_attribute_value_3'],
                'DataType' => 'String',
            ])],
            'MessageDeduplicationId' => 'abcdef',
            'MessageGroupId' => 'abcdef01',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessage.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: AmazonSQS.SendMessage

            {
                "QueueUrl": "queueUrl",
                "MessageBody": "This is a test message",
                "DelaySeconds": 45,
                "MessageAttributes": {
                    "my_attribute_name_1": {
                        "DataType": "String",
                        "StringValue": "my_attribute_value_1"
                    }
                },
                "MessageSystemAttributes": {
                    "AWSTraceHeader": {
                        "DataType": "String",
                        "StringListValues": ["my_attribute_value_2", "my_attribute_value_3"]
                    }
                },
                "MessageDeduplicationId": "abcdef",
                "MessageGroupId": "abcdef01"
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
