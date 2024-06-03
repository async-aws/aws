<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Enum\ShardIteratorType;
use AsyncAws\Kinesis\Input\GetShardIteratorInput;

class GetShardIteratorInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetShardIteratorInput([
            'StreamName' => 'exampleStreamName',
            'ShardId' => 'shardId-000000000001',
            'ShardIteratorType' => ShardIteratorType::LATEST,
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_GetShardIterator.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.GetShardIterator
Accept: application/json

{
  "StreamName": "exampleStreamName",
  "ShardId": "shardId-000000000001",
  "ShardIteratorType": "LATEST"
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
