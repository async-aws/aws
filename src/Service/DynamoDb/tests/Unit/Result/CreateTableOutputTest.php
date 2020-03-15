<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\CreateTableOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateTableOutputTest extends TestCase
{
    public function testCreateTableOutput(): void
    {

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "TableDescription": {
                "AttributeDefinitions": [
                    {
                        "AttributeName": "Artist",
                        "AttributeType": "S"
                    },
                    {
                        "AttributeName": "SongTitle",
                        "AttributeType": "S"
                    }
                ],
                "CreationDateTime": 1.36372808007E9,
                "ItemCount": 0,
                "KeySchema": [
                    {
                        "AttributeName": "Artist",
                        "KeyType": "HASH"
                    },
                    {
                        "AttributeName": "SongTitle",
                        "KeyType": "RANGE"
                    }
                ],
                "ProvisionedThroughput": {
                    "ReadCapacityUnits": 5,
                    "WriteCapacityUnits": 5
                },
                "TableName": "Music",
                "TableSizeBytes": 0,
                "TableStatus": "CREATING"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new CreateTableOutput($client->request('POST', 'http://localhost'), $client);

        self::assertEquals('Artist', $result->getTableDescription()->getAttributeDefinitions()[0]->getAttributeName());
        self::assertEquals('SongTitle', $result->getTableDescription()->getAttributeDefinitions()[1]->getAttributeName());
        self::assertEquals('2013-03-19 21:21:20', $result->getTableDescription()->getCreationDateTime()->format('Y-m-d H:i:s'));
        self::assertEquals('070000', $result->getTableDescription()->getCreationDateTime()->format('u'));
        self::assertEquals('Artist', $result->getTableDescription()->getKeySchema()[0]->getAttributeName());
        self::assertEquals('HASH', $result->getTableDescription()->getKeySchema()[0]->getKeyType());
        self::assertEquals('Music', $result->getTableDescription()->getTableName());
    }
}
