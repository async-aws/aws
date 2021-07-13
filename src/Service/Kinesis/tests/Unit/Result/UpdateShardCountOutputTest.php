<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\UpdateShardCountOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class UpdateShardCountOutputTest extends TestCase
{
    public function testUpdateShardCountOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_UpdateShardCount.html
        $response = new SimpleMockedResponse('{
  "TargetShardCount": 4,
  "StreamName": "exampleStreamName",
  "CurrentShardCount": 2
}');

        $client = new MockHttpClient($response);
        $result = new UpdateShardCountOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('exampleStreamName', $result->getStreamName());
        self::assertSame(2, $result->getCurrentShardCount());
        self::assertSame(4, $result->getTargetShardCount());
    }
}
