<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\ExecuteStatementOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;
use AsyncAws\DynamoDb\ValueObject\ConsumedCapacity;

class ExecuteStatementOutputTest extends TestCase
{
    public function testExecuteStatementOutput(): void
    {
        // see https://docs.aws.amazon.com/dynamodb/latest/APIReference/API_ExecuteStatement.html
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "ConsumedCapacity": {
                "CapacityUnits": 1,
                "TableName": "Reply"
            },
            "Items": [
                {
                    "SongTitle": {
                        "S": "Call Me Today"
                    }
                }
            ],
            "NextToken": "changeIt"
        }');

        $client = new MockHttpClient($response);
        $result = new ExecuteStatementOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $items = $result->getItems(true);
        foreach ($items as $name => $item) {
            self::assertArrayHasKey('SongTitle', $item);
            self::assertEquals('Call Me Today', $item['SongTitle']->getS());
        }
        self::assertInstanceOf(ConsumedCapacity::class, $result->getConsumedCapacity());
        self::assertSame('changeIt', $result->getNextToken());
    }
}
