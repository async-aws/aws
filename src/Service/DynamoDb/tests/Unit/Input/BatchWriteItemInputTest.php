<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\BatchWriteItemInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\PutRequest;
use AsyncAws\DynamoDb\ValueObject\WriteRequest;

class BatchWriteItemInputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_BatchWriteItem.html#API_BatchWriteItem_Examples
     */
    public function testRequest(): void
    {
        $input = new BatchWriteItemInput([
            'RequestItems' => [
                'Forum' => [
                    new WriteRequest(['PutRequest' => new PutRequest([
                        'Item' => [
                            'Name' => new AttributeValue(['S' => 'Amazon DynamoDB']),
                            'Category' => new AttributeValue(['S' => 'Amazon Web Services']),
                        ],
                    ])]),
                    new WriteRequest(['PutRequest' => new PutRequest([
                        'Item' => [
                            'Name' => new AttributeValue(['S' => 'Amazon RDS']),
                            'Category' => new AttributeValue(['S' => 'Amazon Web Services']),
                        ],
                    ])]),
                    new WriteRequest(['PutRequest' => new PutRequest([
                        'Item' => [
                            'Name' => new AttributeValue(['S' => 'Amazon Redshift']),
                            'Category' => new AttributeValue(['S' => 'Amazon Web Services']),
                        ],
                    ])]),
                    new WriteRequest(['PutRequest' => new PutRequest([
                        'Item' => [
                            'Name' => new AttributeValue(['S' => 'Amazon ElastiCache']),
                            'Category' => new AttributeValue(['S' => 'Amazon Web Services']),
                        ],
                    ])]),
                ], ],
            'ReturnConsumedCapacity' => 'TOTAL',
        ]);

        // see example-1.json from SDK
        $expected = '
    POST / HTTP/1.0
    Content-Type: application/x-amz-json-1.0
    X-Amz-Target: DynamoDB_20120810.BatchWriteItem

{
    "RequestItems": {
        "Forum": [
            {
                "PutRequest": {
                    "Item": {
                        "Name": {
                            "S": "Amazon DynamoDB"
                        },
                        "Category": {
                            "S": "Amazon Web Services"
                        }
                    }
                }
            },
            {
                "PutRequest": {
                    "Item": {
                        "Name": {
                            "S": "Amazon RDS"
                        },
                        "Category": {
                            "S": "Amazon Web Services"
                        }
                    }
                }
            },
            {
                "PutRequest": {
                    "Item": {
                        "Name": {
                            "S": "Amazon Redshift"
                        },
                        "Category": {
                            "S": "Amazon Web Services"
                        }
                    }
                }
            },
            {
                "PutRequest": {
                    "Item": {
                        "Name": {
                            "S": "Amazon ElastiCache"
                        },
                        "Category": {
                            "S": "Amazon Web Services"
                        }
                    }
                }
            }
        ]
    },
    "ReturnConsumedCapacity": "TOTAL"
}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
