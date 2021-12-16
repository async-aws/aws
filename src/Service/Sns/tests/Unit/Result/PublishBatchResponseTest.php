<?php

namespace AsyncAws\Sns\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Result\PublishBatchResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PublishBatchResponseTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/sns/latest/api/API_PublishBatch.html
     */
    public function testPublishBatchResponse(): void
    {
        $response = new SimpleMockedResponse('<PublishBatchResponse xmlns="https://sns.amazonaws.com/doc/2010-03-31/">
    <PublishBatchResult>
        <Failed>
        </Failed>
        <Successful>
            <member>
                <Id>qwertyuiop</Id>
                <MessageId>567910cd-659e-55d4-8ccb-5aaf14679dc0</MessageId>
            </member>
        </Successful>        
    </PublishBatchResult>
    <ResponseMetadata>
        <RequestId>d74b8436-ae13-5ab4-a9ff-ce54dfea72a0</RequestId>
    </ResponseMetadata>
</PublishBatchResponse>');

        $client = new MockHttpClient($response);
        $result = new PublishBatchResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(0, $result->getFailed());
        self::assertCount(1, $result->getSuccessful());
        self::assertSame('qwertyuiop', $result->getSuccessful()[0]->getId());
        self::assertSame('567910cd-659e-55d4-8ccb-5aaf14679dc0', $result->getSuccessful()[0]->getMessageId());
    }
}
