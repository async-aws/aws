<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\MessageAttributeValue;
use AsyncAws\Sqs\Input\MessageSystemAttributeValue;
use AsyncAws\Sqs\Input\SendMessageRequest;

class SendMessageRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new SendMessageRequest([
            'QueueUrl' => 'queueUrl',
            'MessageBody' => 'This is a test message',
            'DelaySeconds' => 45,
            'MessageAttributes' => ['my_attribute_name_1' => new MessageAttributeValue([
                'StringValue' => 'my_attribute_value_1',
                'DataType' => 'String',
            ])],
            'MessageSystemAttributes' => ['my_attribute_name_2' => new MessageSystemAttributeValue([
                'StringListValues' => ['my_attribute_value_2', 'my_attribute_value_3'],
                'DataType' => 'String',
            ])],
            'MessageDeduplicationId' => 'abcdef',
            'MessageGroupId' => 'abcdef01',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessage.html */
        $expected = '
Action=SendMessage
&Version=2012-11-05
&QueueUrl=queueUrl
&MessageBody=This+is+a+test+message
&DelaySeconds=45
&MessageAttribute.1.Name=my_attribute_name_1
&MessageAttribute.1.Value.StringValue=my_attribute_value_1
&MessageAttribute.1.Value.DataType=String
&MessageSystemAttribute.1.Name=my_attribute_name_2
&MessageSystemAttribute.1.Value.StringListValue.1=my_attribute_value_2
&MessageSystemAttribute.1.Value.StringListValue.2=my_attribute_value_3
&MessageSystemAttribute.1.Value.DataType=String
&MessageDeduplicationId=abcdef
&MessageGroupId=abcdef01
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->requestBody());
    }
}
