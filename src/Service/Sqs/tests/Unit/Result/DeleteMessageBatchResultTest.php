<?php

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Result\DeleteMessageBatchResult;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteMessageBatchResultTest extends TestCase
{
    public function testDeleteMessageBatchResult(): void
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteMessageBatch.html
        $response = new SimpleMockedResponse(<<<JSON
{
    "Failed": [],
    "Successful": [
        {
            "Id": "msg1"
        },
        {
            "Id": "msg2"
        }
    ]
}
JSON
        );

        $client = new MockHttpClient($response);
        $result = new DeleteMessageBatchResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(2, $result->getSuccessful());
        self::assertSame('msg1', $result->getSuccessful()[0]->getId());
        self::assertSame('msg2', $result->getSuccessful()[1]->getId());
        self::assertCount(0, $result->getFailed());
    }
}
