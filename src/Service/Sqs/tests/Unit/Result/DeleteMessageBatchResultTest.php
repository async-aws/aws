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
        $response = new SimpleMockedResponse(<<<XML
<DeleteMessageBatchResponse>
    <DeleteMessageBatchResult>
        <DeleteMessageBatchResultEntry>
            <Id>msg1</Id>
        </DeleteMessageBatchResultEntry>
        <DeleteMessageBatchResultEntry>
            <Id>msg2</Id>
        </DeleteMessageBatchResultEntry>
    </DeleteMessageBatchResult>
    <ResponseMetadata>
        <RequestId>d6f86b7a-74d1-4439-b43f-196a1e29cd85</RequestId>
    </ResponseMetadata>
</DeleteMessageBatchResponse>
XML
        );

        $client = new MockHttpClient($response);
        $result = new DeleteMessageBatchResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(2, $result->getSuccessful());
        self::assertSame('msg1', $result->getSuccessful()[0]->getId());
        self::assertSame('msg2', $result->getSuccessful()[1]->getId());
        self::assertCount(0, $result->getFailed());
    }
}
