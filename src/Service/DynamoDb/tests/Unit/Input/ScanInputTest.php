<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\ScanInput;

class ScanInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ScanInput([
            'TableName' => 'Reply',
            'ReturnConsumedCapacity' => 'TOTAL',
            'FilterExpression' => 'PostedBy = :val',
            'ExpressionAttributeValues' => [':val' => ['S' => 'joe@example.com']],
        ]);

        // see example-1.json from SDK
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
X-AMZ-Target: DynamoDB_20120810.Scan

{
    "TableName": "Reply",
    "FilterExpression": "PostedBy = :val",
    "ExpressionAttributeValues": {":val": {"S": "joe@example.com"}},
    "ReturnConsumedCapacity": "TOTAL"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
