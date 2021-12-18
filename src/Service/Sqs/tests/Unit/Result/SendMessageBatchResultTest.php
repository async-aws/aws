<?php

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Result\SendMessageBatchResult;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class SendMessageBatchResultTest extends TestCase
{
    public function testSendMessageBatchResult(): void
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessageBatch.html
        $response = new SimpleMockedResponse(<<<XML
<SendMessageBatchResponse>
<SendMessageBatchResult>
    <SendMessageBatchResultEntry>
        <Id>test_msg_001</Id>
        <MessageId>0a5231c7-8bff-4955-be2e-8dc7c50a25fa</MessageId>
        <MD5OfMessageBody>0e024d309850c78cba5eabbeff7cae71</MD5OfMessageBody>
    </SendMessageBatchResultEntry>
    <SendMessageBatchResultEntry>
        <Id>test_msg_002</Id>
        <MessageId>15ee1ed3-87e7-40c1-bdaa-2e49968ea7e9</MessageId>
        <MD5OfMessageBody>7fb8146a82f95e0af155278f406862c2</MD5OfMessageBody>
        <MD5OfMessageAttributes>295c5fa15a51aae6884d1d7c1d99ca50</MD5OfMessageAttributes>
    </SendMessageBatchResultEntry>
</SendMessageBatchResult>
<ResponseMetadata>
    <RequestId>ca1ad5d0-8271-408b-8d0f-1351bf547e74</RequestId>
</ResponseMetadata>
</SendMessageBatchResponse>
XML
        );

        $client = new MockHttpClient($response);
        $result = new SendMessageBatchResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(2, $result->getSuccessful());
        self::assertSame('test_msg_001', $result->getSuccessful()[0]->getId());
        self::assertSame('0a5231c7-8bff-4955-be2e-8dc7c50a25fa', $result->getSuccessful()[0]->getMessageId());
        self::assertSame('0e024d309850c78cba5eabbeff7cae71', $result->getSuccessful()[0]->getMd5OfMessageBody());
        self::assertSame('test_msg_002', $result->getSuccessful()[1]->getId());
        self::assertSame('15ee1ed3-87e7-40c1-bdaa-2e49968ea7e9', $result->getSuccessful()[1]->getMessageId());
        self::assertSame('7fb8146a82f95e0af155278f406862c2', $result->getSuccessful()[1]->getMd5OfMessageBody());
        self::assertSame('295c5fa15a51aae6884d1d7c1d99ca50', $result->getSuccessful()[1]->getMd5OfMessageAttributes());
        self::assertCount(0, $result->getFailed());
    }
}
