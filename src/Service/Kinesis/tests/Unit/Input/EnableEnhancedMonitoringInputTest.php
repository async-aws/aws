<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Enum\MetricsName;
use AsyncAws\Kinesis\Input\EnableEnhancedMonitoringInput;

class EnableEnhancedMonitoringInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new EnableEnhancedMonitoringInput([
            'StreamName' => 'exampleStreamName',
            'ShardLevelMetrics' => [MetricsName::INCOMING_BYTES, MetricsName::OUTGOING_RECORDS],
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_EnableEnhancedMonitoring.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
X-Amz-Target: Kinesis_20131202.EnableEnhancedMonitoring

{
    "StreamName": "exampleStreamName",
    "ShardLevelMetrics": [
        "IncomingBytes", "OutgoingRecords"
    ]
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
