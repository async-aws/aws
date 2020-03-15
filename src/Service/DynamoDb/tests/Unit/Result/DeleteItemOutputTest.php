<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\DeleteItemOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteItemOutputTest extends TestCase
{
    public function testDeleteItemOutput(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "ConsumedCapacity": {
                "CapacityUnits": 1,
                "TableName": "Music"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteItemOutput($client->request('POST', 'http://localhost'), $client);

        self::assertEquals(1, $result->getConsumedCapacity()->getCapacityUnits());
        self::assertEquals('Music', $result->getConsumedCapacity()->getTableName());
    }
}
