<?php

namespace AsyncAws\XRay\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\XRay\Input\PutTraceSegmentsRequest;
use AsyncAws\XRay\Result\PutTraceSegmentsResult;
use AsyncAws\XRay\XRayClient;
use Symfony\Component\HttpClient\MockHttpClient;

class XRayClientTest extends TestCase
{
    public function testPutTraceSegments(): void
    {
        $client = new XRayClient([], new NullProvider(), new MockHttpClient());

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

        self::assertInstanceOf(PutTraceSegmentsResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
