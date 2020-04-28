<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\DescribeTableOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeTableOutputTest extends TestCase
{
    public function testDescribeTableOutput(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "Table": {
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
                    "NumberOfDecreasesToday": 1,
                    "ReadCapacityUnits": 5,
                    "WriteCapacityUnits": 5
                },
                "TableName": "Music",
                "TableSizeBytes": 0,
                "TableStatus": "ACTIVE"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new DescribeTableOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals('Artist', $result->getTable()->getAttributeDefinitions()[0]->getAttributeName());
        self::assertEquals('SongTitle', $result->getTable()->getAttributeDefinitions()[1]->getAttributeName());
        self::assertEquals('S', $result->getTable()->getAttributeDefinitions()[1]->getAttributeType());
        self::assertEquals('Music', $result->getTable()->getTableName());
    }
}
