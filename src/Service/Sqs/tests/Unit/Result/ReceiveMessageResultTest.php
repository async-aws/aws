<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\ReceiveMessageResult;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ReceiveMessageResultTest extends TestCase
{
    public function testReceiveMessageResult()
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ReceiveMessage.html
        $response = new SimpleMockedResponse(<<<JSON
{
    "Messages": [
        {
            "Attributes": {
                "SenderId": "195004372649",
                "ApproximateFirstReceiveTimestamp": "1250700979248",
                "ApproximateReceiveCount": "1",
                "SentTimestamp": "1677112427387"
            },
            "Body": "This is a test message",
            "MD5OfBody": "fafb00f5732ab283681e124bf8747ed1",
            "MessageId": "5fea7756-0ea4-451a-a703-a558b933e274",
            "ReceiptHandle": "AQEBaZ+j5qUoOAoxlmrCQPkBm9njMWXqemmIG6shMHCO6fV20JrQYg/AiZ8JELwLwOu5U61W+aIX5Qzu7GGofxJuvzymr4Ph53RiR0mudj4InLSgpSspYeTRDteBye5tV/txbZDdNZxsi+qqZA9xPnmMscKQqF6pGhnGIKrnkYGl45Nl6GPIZv62LrIRb6mSqOn1fn0yqrvmWuuY3w2UzQbaYunJWGxpzZze21EOBtywknU3Je/g7G9is+c6K9hGniddzhLkK1tHzZKjejOU4jokaiB4nmi0dF3JqLzDsQuPF0Gi8qffhEvw56nl8QCbluSJScFhJYvoagGnDbwOnd9z50L239qtFIgETdpKyirlWwl/NGjWJ45dqWpiW3d2Ws7q"
        }
    ]
}
JSON
        );

        $client = new MockHttpClient($response);
        $result = new ReceiveMessageResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getMessages());
        $message = $result->getMessages()[0];
        self::assertEquals('5fea7756-0ea4-451a-a703-a558b933e274', $message->getMessageId());
        self::assertEquals('fafb00f5732ab283681e124bf8747ed1', $message->getMD5OfBody());
        self::assertArrayHasKey('SenderId', $message->getAttributes());
        self::assertEquals('195004372649', $message->getAttributes()['SenderId']);
        self::assertArrayHasKey('ApproximateFirstReceiveTimestamp', $message->getAttributes());
        self::assertEquals('1250700979248', $message->getAttributes()['ApproximateFirstReceiveTimestamp']);
    }

    public function testReceiveMessageEmptyArray()
    {
        $response = new SimpleMockedResponse(<<<JSON
{
    "Messages": []
}
JSON
        );

        $client = new MockHttpClient($response);
        $result = new ReceiveMessageResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(0, $result->getMessages());
    }

    public function testReceiveMessageEmptyStringLongPolling()
    {
        $response = new SimpleMockedResponse('');

        $client = new MockHttpClient($response);
        $result = new ReceiveMessageResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(0, $result->getMessages());
    }
}
