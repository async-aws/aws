<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Enum\MetricsName;
use AsyncAws\Kinesis\Result\EnhancedMonitoringOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class EnhancedMonitoringOutputTest extends TestCase
{
    public function testEnhancedMonitoringOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DisableEnhancedMonitoring.html
        $response = new SimpleMockedResponse('{
    "StreamName": "exampleStreamName",
    "CurrentShardLevelMetrics": [
        "IncomingBytes",
        "OutgoingRecords"
    ],
    "DesiredShardLevelMetrics": []
}');

        $client = new MockHttpClient($response);
        $result = new EnhancedMonitoringOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('exampleStreamName', $result->getStreamName());
        self::assertSame([MetricsName::INCOMING_BYTES, MetricsName::OUTGOING_RECORDS], $result->getCurrentShardLevelMetrics());
        self::assertSame([], $result->getDesiredShardLevelMetrics());
    }
}
