<?php

namespace AsyncAws\XRay\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\XRay\Input\PutTraceSegmentsRequest;

class PutTraceSegmentsRequestTest extends TestCase
{
    public function testRequest(): void
    {
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

        // see https://docs.aws.amazon.com/xray/latest/api/API_PutTraceSegments.html
        $expected = '
            POST /TraceSegments HTTP/1.0
            Content-type: application/json
            Accept: application/json

            {
            "TraceSegmentDocuments": [
                "{\"name\":\"service-foo\",\"id\":\"1111111111111111\",\"trace_id\":\"1-58406520-a006649127e371903a2de979\",\"start_time\":1480615200.01,\"end_time\":1480615200.09}"
            ]
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
