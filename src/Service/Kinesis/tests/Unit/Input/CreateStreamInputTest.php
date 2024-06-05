<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\CreateStreamInput;

class CreateStreamInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateStreamInput([
            'StreamName' => 'exampleStreamName',
            'ShardCount' => 3,
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_CreateStream.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.CreateStream
Accept: application/json

{
    "StreamName": "exampleStreamName",
    "ShardCount": 3
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
