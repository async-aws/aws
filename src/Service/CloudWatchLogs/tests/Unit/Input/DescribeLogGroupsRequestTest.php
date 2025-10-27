<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit\Input;

use AsyncAws\CloudWatchLogs\Enum\LogGroupClass;
use AsyncAws\CloudWatchLogs\Input\DescribeLogGroupsRequest;
use AsyncAws\Core\Test\TestCase;

class DescribeLogGroupsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeLogGroupsRequest([
            'accountIdentifiers' => ['123456789012'],
            'includeLinkedAccounts' => false,
            'limit' => 1337,
            'logGroupClass' => LogGroupClass::STANDARD,
            'logGroupIdentifiers' => ['logGroupIdentifier1'],
            'logGroupNamePattern' => 'my-log-group-pattern',
            'logGroupNamePrefix' => 'my-log-group',
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_DescribeLogGroups.html
        $expected = 'POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
X-AMZ-TARGET: Logs_20140328.DescribeLogGroups
Accept: application/json

{
    "accountIdentifiers": [
        "123456789012"
    ],
    "includeLinkedAccounts": false,
    "limit": 1337,
    "logGroupClass": "STANDARD",
    "logGroupIdentifiers": [
        "logGroupIdentifier1"
    ],
    "logGroupNamePattern": "my-log-group-pattern",
    "logGroupNamePrefix": "my-log-group"
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
