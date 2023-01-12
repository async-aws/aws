<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit\Input;

use AsyncAws\CloudWatchLogs\Input\CreateLogStreamRequest;
use AsyncAws\Core\Test\TestCase;

class CreateLogStreamRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateLogStreamRequest([
            'logGroupName' => 'my-log-group',
            'logStreamName' => 'my-log-stream',
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_CreateLogStream.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
X-Amz-Target: Logs_20140328.CreateLogStream

{
  "logGroupName": "my-log-group",
  "logStreamName": "my-log-stream"
}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
