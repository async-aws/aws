<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Enum\MetricsName;
use AsyncAws\Kinesis\Input\DescribeStreamInput;
use AsyncAws\Kinesis\KinesisClient;
use AsyncAws\Kinesis\Result\DescribeStreamOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeStreamOutputTest extends TestCase
{
    public function testDescribeStreamOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStream.html
        $response = new SimpleMockedResponse('{
  "StreamDescription": {
    "EnhancedMonitoring":[
       {
          "ShardLevelMetrics":[
             "IncomingBytes",
             "OutgoingRecords"
          ]
       }
    ],
    "HasMoreShards": false,
    "RetentionPeriodHours": 24,
    "StreamCreationTimestamp": 1.468346745E9,
    "Shards": [
      {
        "HashKeyRange": {
          "EndingHashKey": "113427455640312821154458202477256070484",
          "StartingHashKey": "0"
        },
        "SequenceNumberRange": {
          "EndingSequenceNumber": "21269319989741826081360214168359141376",
          "StartingSequenceNumber": "21267647932558653966460912964485513216"
        },
        "ShardId": "shardId-000000000000"
      },
      {
        "HashKeyRange": {
          "EndingHashKey": "226854911280625642308916404954512140969",
          "StartingHashKey": "113427455640312821154458202477256070485"
        },
        "SequenceNumberRange": {
          "StartingSequenceNumber": "21267647932558653966460912964485513217"
        },
        "ShardId": "shardId-000000000001"
      },
      {
        "HashKeyRange": {
          "EndingHashKey": "340282366920938463463374607431768211455",
          "StartingHashKey": "226854911280625642308916404954512140970"
        },
        "SequenceNumberRange": {
          "StartingSequenceNumber": "21267647932558653966460912964485513218"
        },
        "ShardId": "shardId-000000000002"
      }
    ],
    "StreamARN": "arn:aws:kinesis:us-east-1:111122223333:exampleStreamName",
    "StreamName": "exampleStreamName",
    "StreamStatus": "ACTIVE"
  }
}');

        $client = new MockHttpClient($response);
        $result = new DescribeStreamOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new KinesisClient(), new DescribeStreamInput([]));

        self::assertSame([MetricsName::INCOMING_BYTES, MetricsName::OUTGOING_RECORDS], $result->getStreamDescription()->getEnhancedMonitoring()[0]->getShardLevelMetrics());
        self::assertSame(24, $result->getStreamDescription()->getRetentionPeriodHours());
        self::assertCount(3, $result->getStreamDescription()->getShards());
    }
}
