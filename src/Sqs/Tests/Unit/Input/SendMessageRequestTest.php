<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Sqs\Input\SendMessageRequest;
use PHPUnit\Framework\TestCase;

class SendMessageRequestTest extends TestCase
{
    public function testRequestBody()
    {
        $input = SendMessageRequest::create([
            'MessageBody' => 'This is a test message',
            'MessageAttributes' => [
                'my_attribute_name_1' => [
                    'StringValue' => 'my_attribute_value_1',
                    'DataType' => 'String',
                ],
                'my_attribute_name_2' => [
                    'StringListValues' => [
                        'my_attribute_value_2',
                        'my_attribute_value_3',
                    ],
                    'DataType' => 'String',
                ],
            ],
        ]);

        $encoded = http_build_query($input->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        $expected = 'Action=SendMessage
&Version=2012-11-05
&MessageBody=This+is+a+test+message
&MessageAttribute.1.Name=my_attribute_name_1
&MessageAttribute.1.Value.StringValue=my_attribute_value_1
&MessageAttribute.1.Value.DataType=String
&MessageAttribute.2.Name=my_attribute_name_2
&MessageAttribute.2.Value.StringListValue.1=my_attribute_value_2
&MessageAttribute.2.Value.StringListValue.2=my_attribute_value_3
&MessageAttribute.2.Value.DataType=String';

        self::assertEquals($expected, \str_replace('&', "\n&", $encoded));
    }
}
