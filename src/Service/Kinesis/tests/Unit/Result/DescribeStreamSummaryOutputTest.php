<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\DescribeStreamSummaryOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeStreamSummaryOutputTest extends TestCase
{
    public function testDescribeStreamSummaryOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStreamSummary.html
        $response = new SimpleMockedResponse('{
   "StreamDescriptionSummary": {
      "ConsumerCount": 1,
      "EncryptionType": "type",
      "EnhancedMonitoring": [
         {
            "ShardLevelMetrics": [ "metric" ]
         }
      ],
      "KeyId": "key",
      "OpenShardCount": 2,
      "RetentionPeriodHours": 24,
      "StreamARN": "arn",
      "StreamCreationTimestamp": 123456,
      "StreamName": "name",
      "StreamStatus": "status"
   }
}');

        $client = new MockHttpClient($response);
        $result = new DescribeStreamSummaryOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn', $result->getStreamDescriptionSummary()->getStreamArn());
    }
}
