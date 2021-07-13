<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\IncreaseStreamRetentionPeriodInput;

class IncreaseStreamRetentionPeriodInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new IncreaseStreamRetentionPeriodInput([
            'StreamName' => 'examplestream',
            'RetentionPeriodHours' => 96,
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_IncreaseStreamRetentionPeriod.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
X-Amz-Target: Kinesis_20131202.IncreaseStreamRetentionPeriod

{
    "StreamName": "examplestream",
    "RetentionPeriodHours": 96
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
