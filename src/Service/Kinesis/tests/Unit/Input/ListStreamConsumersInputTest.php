<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\ListStreamConsumersInput;

class ListStreamConsumersInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListStreamConsumersInput([
            'StreamARN' => 'xxx',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListStreamConsumers.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.ListStreamConsumers

{
    "StreamARN": "xxx"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
