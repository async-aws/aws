<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\MergeShardsInput;

class MergeShardsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new MergeShardsInput([
            'StreamName' => 'exampleStreamName',
            'ShardToMerge' => 'shardId-000000000000',
            'AdjacentShardToMerge' => 'shardId-000000000001',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_MergeShards.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.MergeShards
Accept: application/json

{
  "StreamName": "exampleStreamName",
  "ShardToMerge": "shardId-000000000000",
  "AdjacentShardToMerge": "shardId-000000000001"
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
