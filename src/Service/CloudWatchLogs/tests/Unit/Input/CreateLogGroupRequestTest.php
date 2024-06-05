<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit\Input;

use AsyncAws\CloudWatchLogs\Input\CreateLogGroupRequest;
use AsyncAws\Core\Test\TestCase;

class CreateLogGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateLogGroupRequest([
            'logGroupName' => 'my-log-group',
            'kmsKeyId' => 'arn:aws:kms:us-east-1:123456789012:key/abcd1234-a123-456a-a12b-a123b456c789',
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_CreateLogGroup.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Logs_20140328.CreateLogGroup
Accept: application/json

{
  "logGroupName": "my-log-group",
  "kmsKeyId": "arn:aws:kms:us-east-1:123456789012:key/abcd1234-a123-456a-a12b-a123b456c789"
}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
