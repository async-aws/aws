<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\QueryInput;

class QueryInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new QueryInput([
            'TableName' => 'Reply',
            'IndexName' => 'PostedBy-Index',
            'Limit' => 1337,
            'ConsistentRead' => true,
            'ReturnConsumedCapacity' => 'TOTAL',
            'ProjectionExpression' => 'Id, PostedBy, ReplyDateTime',
            'KeyConditionExpression' => 'Id = :v1 AND PostedBy BETWEEN :v2a AND :v2b',
            'ExpressionAttributeValues' => [
                ':v1' => ['S' => 'Amazon DynamoDB#DynamoDB Thread 1'],
                ':v2a' => ['S' => 'User A'],
                ':v2b' => ['S' => 'User C'],
            ],
        ]);

        // see example-1.json from SDK
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
X-AMZ-Target: DynamoDB_20120810.Query

{
    "TableName": "Reply",
    "IndexName": "PostedBy-Index",
    "Limit": 1337,
    "ConsistentRead": true,
    "ProjectionExpression": "Id, PostedBy, ReplyDateTime",
    "KeyConditionExpression": "Id = :v1 AND PostedBy BETWEEN :v2a AND :v2b",
    "ExpressionAttributeValues": {
        ":v1": {"S": "Amazon DynamoDB#DynamoDB Thread 1"},
        ":v2a": {"S": "User A"},
        ":v2b": {"S": "User C"}
    },
    "ReturnConsumedCapacity": "TOTAL"
}
                        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
