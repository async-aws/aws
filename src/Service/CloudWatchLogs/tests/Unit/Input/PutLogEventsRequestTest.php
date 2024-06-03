<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit\Input;

use AsyncAws\CloudWatchLogs\Input\PutLogEventsRequest;
use AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent;
use AsyncAws\Core\Test\TestCase;

class PutLogEventsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutLogEventsRequest([
            'logGroupName' => 'my-log-group',
            'logStreamName' => 'my-log-stream',
            'logEvents' => [
                new InputLogEvent([
                    'timestamp' => 1396035378988,
                    'message' => 'Example event 1',
                ]),
                new InputLogEvent([
                    'timestamp' => 1396035378988,
                    'message' => 'Example event 2',
                ]),
                new InputLogEvent([
                    'timestamp' => 1396035378989,
                    'message' => 'Example event 3',
                ]),
            ],
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_PutLogEvents.html
        $expected = '
                    POST / HTTP/1.0
                    Content-Type: application/x-amz-json-1.1
                    X-AMZ-TARGET: Logs_20140328.PutLogEvents
                    Accept: application/json

                    {
                        "logGroupName": "my-log-group",
                        "logStreamName": "my-log-stream",
                        "logEvents": [
                            {
                                "timestamp": 1396035378988,
                                "message": "Example event 1"
                            },
                            {
                                "timestamp": 1396035378988,
                                "message": "Example event 2"
                            },
                            {
                                "timestamp": 1396035378989,
                                "message": "Example event 3"
                            }
                        ]
                    }
                    ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
