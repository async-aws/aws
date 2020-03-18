<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\UpdateItemInput;

class UpdateItemInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UpdateItemInput([
            'TableName' => 'Thread',
            'Key' => [
                'ForumName' => ['S' => 'Amazon DynamoDB'],
                'Subject' => ['S' => 'Maximum number of items?'],
            ],
            'ReturnValues' => 'ALL_NEW',
            'UpdateExpression' => 'set LastPostedBy = :val1',
            'ConditionExpression' => 'LastPostedBy = :val2',
            'ExpressionAttributeValues' => [
                ':val1' => ['S' => 'alice@example.com'],
                ':val2' => ['S' => 'fred@example.com'],
            ],
        ]);

        // see example-1.json from SDK
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
X-AMZ-Target: DynamoDB_20120810.UpdateItem

{
    "TableName": "Thread",
    "Key": {
        "ForumName": {
            "S": "Amazon DynamoDB"
        },
        "Subject": {
            "S": "Maximum number of items?"
        }
    },
    "UpdateExpression": "set LastPostedBy = :val1",
    "ConditionExpression": "LastPostedBy = :val2",
    "ExpressionAttributeValues": {
        ":val1": {"S": "alice@example.com"},
        ":val2": {"S": "fred@example.com"}
    },
    "ReturnValues": "ALL_NEW"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
