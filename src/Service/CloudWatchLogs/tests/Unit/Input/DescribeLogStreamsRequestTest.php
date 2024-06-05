<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit\Input;

use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;
use AsyncAws\Core\Test\TestCase;

class DescribeLogStreamsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeLogStreamsRequest([
            'logGroupName' => 'foo',
            'logStreamNamePrefix' => 'bar',
            'orderBy' => 'LogStreamName',
            'descending' => false,
            'nextToken' => 'foobar',
            'limit' => 1337,
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_DescribeLogStreams.html
        $expected = '
                    POST / HTTP/1.0
                    Content-Type: application/x-amz-json-1.1
                    X-AMZ-TARGET: Logs_20140328.DescribeLogStreams
                    Accept: application/json

                    {
                        "logGroupName": "foo",
                        "logStreamNamePrefix": "bar",
                        "orderBy": "LogStreamName",
                        "descending": false,
                        "nextToken": "foobar",
                        "limit": 1337
                    }
                    ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
