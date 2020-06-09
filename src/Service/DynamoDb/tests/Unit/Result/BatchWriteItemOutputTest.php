<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\BatchWriteItemOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class BatchWriteItemOutputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_BatchWriteItem.html#API_BatchWriteItem_Examples
     */
    public function testBatchWriteItemOutput(): void
    {
        $response = new SimpleMockedResponse('
{
    "UnprocessedItems": {
        "Forum": [
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
    "ConsumedCapacity": [
        {
            "TableName": "Forum",
            "CapacityUnits": 3
        }
    ]
}');

        $client = new MockHttpClient($response);
        $result = new BatchWriteItemOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertArrayHasKey('Forum', $result->getUnprocessedItems());
        $forumResult = $result->getUnprocessedItems()['Forum'][0];
        self::assertEquals('Amazon ElastiCache', $forumResult->getPutRequest()->getItem()['Name']->getS());
    }
}
