<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\DescribeStreamConsumerInput;

class DescribeStreamConsumerInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeStreamConsumerInput([
            'StreamARN' => 'xxx',
            'ConsumerName' => 'demo',
            'ConsumerARN' => 'xxx',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStreamConsumer.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.DescribeStreamConsumer
Accept: application/json

{
    "StreamARN": "xxx",
    "ConsumerName": "demo",
    "ConsumerARN": "xxx"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
