<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\TransactWriteItemsInput;
use AsyncAws\DynamoDb\ValueObject\Put;
use AsyncAws\DynamoDb\ValueObject\TransactWriteItem;

class TransactWriteItemsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new TransactWriteItemsInput([
            'TransactItems' => [
                new TransactWriteItem([
                    'Put' => new Put([
                        'TableName' => 'Thread',
                        'Item' => [
                            'ForumName' => ['S' => 'Amazon DynamoDB'],
                            'Subject' => ['S' => 'How do I update multiple items?'],
                            'Tags' => ['SS' => ['Update', 'Multiple Items', 'HelpMe']],
                            'EmptyM' => ['M' => []],
                        ],
                        'ConditionExpression' => 'ForumName <> :f and Subject <> :s',
                        'ExpressionAttributeValues' => [
                            ':f' => ['S' => 'Amazon DynamoDB'],
                            ':s' => ['S' => 'How do I update multiple items?'],
                        ],
                    ]),
                ]),
            ],
            'ClientRequestToken' => 'aaaabbbbccccddddeeeeffff',
        ]);

        // see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_TransactWriteItems.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
x-amz-target: DynamoDB_20120810.TransactWriteItems
Accept: application/json

{
    "TransactItems": [
        {
            "Put": {
                "Item": {
                    "ForumName": {
                        "S": "Amazon DynamoDB"
                    },
                    "Subject": {
                        "S": "How do I update multiple items?"
                    },
                    "Tags": {
                        "SS": ["Update", "Multiple Items", "HelpMe"]
                    },
                    "EmptyM": {
                        "M": {}
                    }
                },
                "TableName": "Thread",
                "ConditionExpression": "ForumName <> :f and Subject <> :s",
                "ExpressionAttributeValues": {
                    ":f": {
                        "S": "Amazon DynamoDB"
                    },
                    ":s": {
                        "S": "How do I update multiple items?"
                    }
                }
            }
        }
    ],
    "ClientRequestToken": "aaaabbbbccccddddeeeeffff"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
