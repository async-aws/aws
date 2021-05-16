<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\EnhancedMonitoringOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class EnhancedMonitoringOutputTest extends TestCase
{
    public function testEnhancedMonitoringOutput(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DisableEnhancedMonitoring.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new EnhancedMonitoringOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getStreamName());
        // self::assertTODO(expected, $result->getCurrentShardLevelMetrics());
        // self::assertTODO(expected, $result->getDesiredShardLevelMetrics());
    }
}
