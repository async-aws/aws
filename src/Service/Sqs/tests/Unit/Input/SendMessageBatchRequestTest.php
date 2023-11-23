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
            Content-Type: application/x-amz-json-1.0
            x-amz-target: AmazonSQS.SendMessageBatch

            {
                "QueueUrl": "queueUrl",
                "Entries": [
                    {
                        "Id": "qwertyuiop",
                        "MessageBody": "This is a test message",
                        "DelaySeconds": 45,
                        "MessageAttributes": {
                            "my_attribute_name_1": {
                                "DataType": "String",
                                "StringValue": "my_attribute_value_1"
                            }
                        },
                        "MessageDeduplicationId": "abcdef",
                        "MessageGroupId": "abcdef01"
                    }
                ]
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
