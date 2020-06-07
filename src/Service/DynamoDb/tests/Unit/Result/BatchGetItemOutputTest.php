<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\BatchGetItemInput;
use AsyncAws\DynamoDb\Result\BatchGetItemOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class BatchGetItemOutputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_BatchGetItem.html#API_BatchGetItem_Examples
     */
    public function testBatchGetItemOutput(): void
    {
        $response = new SimpleMockedResponse('{
    "Responses": {
        "Forum": [
            {
                "Name":{
                    "S":"Amazon DynamoDB"
                },
                "Threads":{
                    "N":"5"
                },
                "Messages":{
                    "N":"19"
                },
                "Views":{
                    "N":"35"
                }
            },
            {
                "Name":{
                    "S":"Amazon RDS"
                },
                "Threads":{
                    "N":"8"
                },
                "Messages":{
                    "N":"32"
                },
                "Views":{
                    "N":"38"
                }
            },
            {
                "Name":{
                    "S":"Amazon Redshift"
                },
                "Threads":{
                    "N":"12"
                },
                "Messages":{
                    "N":"55"
                },
                "Views":{
                    "N":"47"
                }
            }
        ],
        "Thread": [
            {
                "Tags":{
                    "SS":["Reads","MultipleUsers"]
                },
                "Message":{
                    "S":"How many users can read a single data item at a time? Are there any limits?"
                }
            }
        ]
    },
    "UnprocessedKeys": {
    },
    "ConsumedCapacity": [
        {
            "TableName": "Forum",
            "CapacityUnits": 3
        },
        {
            "TableName": "Thread",
            "CapacityUnits": 1
        }
    ]
}');

        $client = new MockHttpClient($response);
        $result = new BatchGetItemOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new DynamoDbClient(), new BatchGetItemInput([]));

        self::assertEmpty($result->getUnprocessedKeys());
        $responses = $result->getResponses();
        self::assertArrayHasKey('Forum', $responses);
        self::assertArrayHasKey('Thread', $responses);
        self::assertEquals('Amazon DynamoDB', $responses['Forum'][0]['Name']->getS());
    }
}
