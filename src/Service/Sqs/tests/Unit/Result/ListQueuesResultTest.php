<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Input\ListQueuesRequest;
use AsyncAws\Sqs\Result\ListQueuesResult;
use AsyncAws\Sqs\SqsClient;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListQueuesResultTest extends TestCase
{
    public function testListQueuesResult()
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ListQueues.html
        $response = new SimpleMockedResponse(<<<JSON
{
    "QueueUrls": [
        "https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue",
        "https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue1648169377027",
        "https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue1648169549830",
        "https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue1648227401019",
        "https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue1648248132466",
        "https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue1649201932174",
        "https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue2"
    ]
}
JSON
        );

        $client = new MockHttpClient($response);
        $result = new ListQueuesResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SqsClient(), new ListQueuesRequest());

        self::assertContains('https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue', $result->getQueueUrls());
        self::assertContains('https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue', $result);
    }
}
