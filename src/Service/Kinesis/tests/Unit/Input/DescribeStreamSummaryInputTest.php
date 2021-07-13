<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\DescribeStreamSummaryInput;

class DescribeStreamSummaryInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeStreamSummaryInput([
            'StreamName' => 'exampleStreamName',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStreamSummary.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.DescribeStreamSummary

{
    "StreamName": "exampleStreamName"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
