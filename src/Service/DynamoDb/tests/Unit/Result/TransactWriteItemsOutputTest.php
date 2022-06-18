<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\TransactWriteItemsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class TransactWriteItemsOutputTest extends TestCase
{
    public function testTransactWriteItemsOutput(): void
    {
        // see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_TransactWriteItems.html
        $response = new SimpleMockedResponse('{
            "ConsumedCapacity": [{
                "CapacityUnits": 2.0,
                "TableName": "Music"
            }],
            "ItemCollectionMetrics": []
        }');

        $client = new MockHttpClient($response);
        $result = new TransactWriteItemsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getConsumedCapacity());
        self::assertSame(2.0, $result->getConsumedCapacity()[0]->getCapacityUnits());
        self::assertSame('Music', $result->getConsumedCapacity()[0]->getTableName());
        self::assertSame([], $result->getItemCollectionMetrics());
    }
}
