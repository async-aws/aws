<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\UpdateTableOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class UpdateTableOutputTest extends TestCase
{
    public function testUpdateTableOutput(): void
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
                "CreationDateTime": "1421866952.062",
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
                    "LastIncreaseDateTime": "1421874759.194",
                    "NumberOfDecreasesToday": 1,
                    "ReadCapacityUnits": 1,
                    "WriteCapacityUnits": 1
                },
                "TableName": "MusicCollection",
                "TableSizeBytes": 0,
                "TableStatus": "UPDATING"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateTableOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals('Artist', $result->getTableDescription()->getAttributeDefinitions()[0]->getAttributeName());
        self::assertEquals('SongTitle', $result->getTableDescription()->getAttributeDefinitions()[1]->getAttributeName());
        self::assertEquals('2015-01-21 19:02:32', $result->getTableDescription()->getCreationDateTime()->format('Y-m-d H:i:s'));
        self::assertEquals('062000', $result->getTableDescription()->getCreationDateTime()->format('u'));
        self::assertEquals('Artist', $result->getTableDescription()->getKeySchema()[0]->getAttributeName());
        self::assertEquals('HASH', $result->getTableDescription()->getKeySchema()[0]->getKeyType());
        self::assertEquals('MusicCollection', $result->getTableDescription()->getTableName());
    }
}
