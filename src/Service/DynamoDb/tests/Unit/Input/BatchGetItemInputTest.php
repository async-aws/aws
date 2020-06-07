<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\BatchGetItemInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\KeysAndAttributes;

class BatchGetItemInputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_BatchGetItem.html#API_BatchGetItem_Examples
     */
    public function testRequest(): void
    {
        $input = new BatchGetItemInput([
            'RequestItems' => [
                'Forum' => new KeysAndAttributes([
                    'Keys' => [
                        ['Name' => new AttributeValue(['S' => 'Amazon DynamoDB'])],
                        ['Name' => new AttributeValue(['S' => 'Amazon RDS'])],
                        ['Name' => new AttributeValue(['S' => 'Amazon Redshift'])],
                    ],
                    'ProjectionExpression' => 'Name, Threads, Messages, Views',
                ]),
                'Thread' => new KeysAndAttributes([
                    'Keys' => [
                        [
                            'ForumName' => new AttributeValue(['S' => 'Amazon DynamoDB']),
                            'Subject' => new AttributeValue(['S' => 'Concurrent reads']),
                        ],
                    ],
                    'ProjectionExpression' => 'Tags, Message',
                ]),
            ],
            'ReturnConsumedCapacity' => 'TOTAL',
        ]);

        // see example-1.json from SDK
        $expected = '
    POST / HTTP/1.0
    Content-Type: application/x-amz-json-1.0
    x-amz-target: DynamoDB_20120810.BatchGetItem

{
    "RequestItems": {
        "Forum": {
            "Keys": [
                {
                    "Name":{"S":"Amazon DynamoDB"}
                },
                {
                    "Name":{"S":"Amazon RDS"}
                },
                {
                    "Name":{"S":"Amazon Redshift"}
                }
            ],
            "ProjectionExpression":"Name, Threads, Messages, Views"
        },
        "Thread": {
            "Keys": [
                {
                    "ForumName":{"S":"Amazon DynamoDB"},
                    "Subject":{"S":"Concurrent reads"}
                }
            ],
            "ProjectionExpression":"Tags, Message"
        }
    },
    "ReturnConsumedCapacity": "TOTAL"
}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
