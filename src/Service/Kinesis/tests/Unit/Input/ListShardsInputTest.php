<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\ListShardsInput;

class ListShardsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListShardsInput([
            'StreamName' => 'exampleStreamName',
            'MaxResults' => 3,
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListShards.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.ListShards
Accept: application/json

{
    "StreamName": "exampleStreamName",
    "MaxResults": 3
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
