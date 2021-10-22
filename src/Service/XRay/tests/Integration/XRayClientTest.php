<?php

namespace AsyncAws\XRay\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\XRay\Input\PutTraceSegmentsRequest;
use AsyncAws\XRay\XRayClient;

class XRayClientTest extends TestCase
{
    public function testPutTraceSegments(): void
    {
        self::markTestIncomplete('Cannot test PutTraceSegments.');

        $client = $this->getClient();

        $input = new PutTraceSegmentsRequest([
            'TraceSegmentDocuments' => [
                json_encode([
                    'name' => 'service-foo',
                    'id' => '1111111111111111',
                    'trace_id' => '1-58406520-a006649127e371903a2de979',
                    'start_time' => 1480615200.010,
                    'end_time' => 1480615200.090,
                ]),
            ],
        ]);
        $result = $client->putTraceSegments($input);

        $result->resolve();

        self::assertSame([], $result->getUnprocessedTraceSegments());
    }

    private function getClient(): XRayClient
    {
        self::fail('Not implemented');

        return new XRayClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
