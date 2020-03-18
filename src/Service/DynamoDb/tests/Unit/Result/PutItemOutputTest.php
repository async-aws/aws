<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\PutItemOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class PutItemOutputTest extends TestCase
{
    public function testPutItemOutput(): void
    {

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "ConsumedCapacity": {
                "CapacityUnits": 1,
                "TableName": "Music"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new PutItemOutput(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertEquals(1, $result->getConsumedCapacity()->getCapacityUnits());
        self::assertEquals('Music', $result->getConsumedCapacity()->getTableName());
    }
}
