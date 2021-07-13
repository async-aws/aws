<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\PutRecordOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutRecordOutputTest extends TestCase
{
    public function testPutRecordOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecord.html
        $response = new SimpleMockedResponse('{
  "SequenceNumber": "21269319989653637946712965403778482177",
  "ShardId": "shardId-000000000001"
}');

        $client = new MockHttpClient($response);
        $result = new PutRecordOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('shardId-000000000001', $result->getShardId());
        self::assertSame('21269319989653637946712965403778482177', $result->getSequenceNumber());
    }
}
