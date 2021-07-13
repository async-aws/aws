<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\DescribeLimitsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeLimitsOutputTest extends TestCase
{
    public function testDescribeLimitsOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeLimits.html
        $response = new SimpleMockedResponse('{
    "OpenShardCount": 20,
    "ShardLimit": 70
}');

        $client = new MockHttpClient($response);
        $result = new DescribeLimitsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(70, $result->getShardLimit());
        self::assertSame(20, $result->getOpenShardCount());
    }
}
