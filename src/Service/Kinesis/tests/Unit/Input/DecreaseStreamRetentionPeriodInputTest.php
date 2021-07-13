<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\DecreaseStreamRetentionPeriodInput;

class DecreaseStreamRetentionPeriodInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DecreaseStreamRetentionPeriodInput([
            'StreamName' => 'examplestream',
            'RetentionPeriodHours' => 24,
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DecreaseStreamRetentionPeriod.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
X-Amz-Target: Kinesis_20131202.DecreaseStreamRetentionPeriod

{
    "StreamName": "examplestream",
    "RetentionPeriodHours": 24
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
