<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\SendMessageResult;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class SendMessageResultTest extends TestCase
{
    public function testSendMessageResult()
    {
        $response = new SimpleMockedResponse(<<<XML
<SendMessageResponse>
    <SendMessageResult>
        <MD5OfMessageBody>fafb00f5732ab283681e124bf8747ed1</MD5OfMessageBody>
        <MD5OfMessageAttributes>3ae8f24a165a8cedc005670c81a27295</MD5OfMessageAttributes>
        <MessageId>5fea7756-0ea4-451a-a703-a558b933e274</MessageId>
    </SendMessageResult>
    <ResponseMetadata>
        <RequestId>27daac76-34dd-47df-bd01-1f6e873584a0</RequestId>
    </ResponseMetadata>
</SendMessageResponse>
XML
        );

        $client = new MockHttpClient($response);
        $result = new SendMessageResult($client->request('POST', 'http://localhost'), $client);

        self::assertEquals('5fea7756-0ea4-451a-a703-a558b933e274', $result->getMessageId());
        self::assertEquals('fafb00f5732ab283681e124bf8747ed1', $result->getMD5OfMessageBody());
        self::assertEquals('3ae8f24a165a8cedc005670c81a27295', $result->getMD5OfMessageAttributes());
    }
}
