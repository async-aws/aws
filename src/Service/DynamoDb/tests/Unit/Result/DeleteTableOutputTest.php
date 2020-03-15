<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\DeleteTableOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteTableOutputTest extends TestCase
{
    public function testDeleteTableOutput(): void
    {

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "TableDescription": {
                "ItemCount": 0,
                "ProvisionedThroughput": {
                    "NumberOfDecreasesToday": 1,
                    "ReadCapacityUnits": 5,
                    "WriteCapacityUnits": 6
                },
                "TableName": "Music",
                "TableSizeBytes": 0,
                "TableStatus": "DELETING"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteTableOutput($client->request('POST', 'http://localhost'), $client);

        self::assertEquals(0, $result->getTableDescription()->getItemCount());
        self::assertEquals(1, $result->getTableDescription()->getProvisionedThroughput()->getNumberOfDecreasesToday());
        self::assertEquals(5, $result->getTableDescription()->getProvisionedThroughput()->getReadCapacityUnits());
        self::assertEquals(6, $result->getTableDescription()->getProvisionedThroughput()->getWriteCapacityUnits());
        self::assertEquals('Music', $result->getTableDescription()->getTableName());
        self::assertEquals('DELETING', $result->getTableDescription()->getTableStatus());
    }
}
