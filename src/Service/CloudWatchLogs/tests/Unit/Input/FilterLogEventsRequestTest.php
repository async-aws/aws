<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit\Input;

use AsyncAws\CloudWatchLogs\Input\FilterLogEventsRequest;
use AsyncAws\Core\Test\TestCase;

class FilterLogEventsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new FilterLogEventsRequest([
            'logGroupName' => 'foo',
            'logStreamNamePrefix' => 'bar',
            'startTime' => 1337,
            'endTime' => 1338,
            'filterPattern' => 'ERROR',
            'nextToken' => 'foobar',
            'limit' => 10,
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_FilterLogEvents.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: Logs_20140328.FilterLogEvents

            {
               "logGroupName": "foo",
               "logStreamNamePrefix": "bar",
               "startTime": 1337,
               "endTime": 1338,
               "filterPattern": "ERROR",
               "limit": 10,
               "nextToken": "foobar"
            }
            ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
