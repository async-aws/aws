<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\GetQueueUrlResult;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetQueueUrlResultTest extends TestCase
{
    public function testGetQueueUrlResult()
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueUrl.html
        $response = new SimpleMockedResponse(<<<JSON
{
    "QueueUrl": "https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue"
}
JSON
        );

        $client = new MockHttpClient($response);
        $result = new GetQueueUrlResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals('https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue', $result->getQueueUrl());
    }
}
