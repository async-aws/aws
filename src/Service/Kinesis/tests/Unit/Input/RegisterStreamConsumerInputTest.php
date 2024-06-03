<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\RegisterStreamConsumerInput;

class RegisterStreamConsumerInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new RegisterStreamConsumerInput([
            'StreamARN' => 'xxx',
            'ConsumerName' => 'demo',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_RegisterStreamConsumer.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.RegisterStreamConsumer
Accept: application/json

{
    "StreamARN": "xxx",
    "ConsumerName": "demo"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
