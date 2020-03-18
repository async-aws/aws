<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\CreateTableInput;
use AsyncAws\DynamoDb\ValueObject\AttributeDefinition;
use AsyncAws\DynamoDb\ValueObject\KeySchemaElement;
use AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndex;
use AsyncAws\DynamoDb\ValueObject\Projection;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;
use AsyncAws\DynamoDb\ValueObject\Tag;

class CreateTableInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateTableInput([
            'AttributeDefinitions' => [new AttributeDefinition([
                'AttributeName' => 'ForumName',
                'AttributeType' => 'S',
            ])],
            'TableName' => 'Thread',
            'KeySchema' => [new KeySchemaElement([
                'AttributeName' => 'ForumName',
                'KeyType' => 'HASH',
            ])],
            'LocalSecondaryIndexes' => [new LocalSecondaryIndex([
                'IndexName' => 'LastPostIndex',
                'KeySchema' => [new KeySchemaElement([
                    'AttributeName' => 'ForumName',
                    'KeyType' => 'HASH',
                ])],
                'Projection' => new Projection([
                    'ProjectionType' => 'KEYS_ONLY',
                ]),
            ])],
            'BillingMode' => 'PROVISIONED',
            'ProvisionedThroughput' => new ProvisionedThroughput([
                'ReadCapacityUnits' => 1337,
                'WriteCapacityUnits' => 1337,
            ]),
            'Tags' => [new Tag([
                'Key' => 'Owner',
                'Value' => 'BlueTeam',
            ])],
        ]);

        // see example-1.json from SDK
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
X-AMZ-Target: DynamoDB_20120810.CreateTable

{
    "AttributeDefinitions": [
        {
            "AttributeName": "ForumName",
            "AttributeType": "S"
        }
    ],
    "TableName": "Thread",
    "KeySchema": [
        {
            "AttributeName": "ForumName",
            "KeyType": "HASH"
        }
    ],
    "LocalSecondaryIndexes": [
        {
            "IndexName": "LastPostIndex",
            "KeySchema": [
                {
                    "AttributeName": "ForumName",
                    "KeyType": "HASH"
                }
            ],
            "Projection": {
                "ProjectionType": "KEYS_ONLY"
            }
        }
    ],
    "BillingMode": "PROVISIONED",
    "ProvisionedThroughput": {
        "ReadCapacityUnits": 1337,
        "WriteCapacityUnits": 1337
    },
    "Tags": [
      {
         "Key": "Owner",
         "Value": "BlueTeam"
      }
   ]
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
