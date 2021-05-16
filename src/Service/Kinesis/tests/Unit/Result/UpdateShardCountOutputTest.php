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
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_UpdateShardCount.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateShardCountOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getStreamName());
        self::assertSame(1337, $result->getCurrentShardCount());
        self::assertSame(1337, $result->getTargetShardCount());
    }
}
