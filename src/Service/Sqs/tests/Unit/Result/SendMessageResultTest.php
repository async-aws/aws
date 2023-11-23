<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\SendMessageResult;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class SendMessageResultTest extends TestCase
{
    public function testSendMessageResult()
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessage.html
        $response = new SimpleMockedResponse(<<<JSON
{
    "MD5OfMessageAttributes": "3ae8f24a165a8cedc005670c81a27295",
    "MD5OfMessageBody": "fafb00f5732ab283681e124bf8747ed1",
    "MessageId": "5fea7756-0ea4-451a-a703-a558b933e274"
}
JSON
        );

        $client = new MockHttpClient($response);
        $result = new SendMessageResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals('5fea7756-0ea4-451a-a703-a558b933e274', $result->getMessageId());
        self::assertEquals('fafb00f5732ab283681e124bf8747ed1', $result->getMD5OfMessageBody());
        self::assertEquals('3ae8f24a165a8cedc005670c81a27295', $result->getMD5OfMessageAttributes());
    }
}
