<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\SplitShardInput;

class SplitShardInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SplitShardInput([
            'StreamName' => 'exampleStreamName',
            'ShardToSplit' => 'shardId-000000000000',
            'NewStartingHashKey' => '10',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_SplitShard.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
X-Amz-Target: Kinesis_20131202.SplitShard

{
  "StreamName": "exampleStreamName",
  "ShardToSplit": "shardId-000000000000",
  "NewStartingHashKey": "10"
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
