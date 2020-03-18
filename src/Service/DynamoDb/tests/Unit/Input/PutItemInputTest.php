<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\PutItemInput;

class PutItemInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutItemInput([
            'TableName' => 'Thread',
            'Item' => [
                'ForumName' => ['S' => 'Amazon DynamoDB'],
                'Subject' => ['S' => 'How do I update multiple items?'],
                'Tags' => ['SS' => ['Update', 'Multiple Items', 'HelpMe']],
            ],
            'ReturnConsumedCapacity' => 'TOTAL',
            'ConditionExpression' => 'ForumName <> :f and Subject <> :s',
            'ExpressionAttributeValues' => [
                ':f' => ['S' => 'Amazon DynamoDB'],
                ':s' => ['S' => 'How do I update multiple items?'],
            ],
        ]);

        // see example-1.json from SDK
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
X-AMZ-Target: DynamoDB_20120810.PutItem

{
    "TableName": "Thread",
    "Item": {
        "ForumName": {
            "S": "Amazon DynamoDB"
        },
        "Subject": {
            "S": "How do I update multiple items?"
        },
        "Tags": {
            "SS": ["Update","Multiple Items","HelpMe"]
        }
    },
    "ReturnConsumedCapacity": "TOTAL",
    "ConditionExpression": "ForumName <> :f and Subject <> :s",
    "ExpressionAttributeValues": {
        ":f": {"S": "Amazon DynamoDB"},
        ":s": {"S": "How do I update multiple items?"}
    }
}
                        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
