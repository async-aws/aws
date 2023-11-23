<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\CreateQueueResult;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateQueueResultTest extends TestCase
{
    public function testCreateQueueResult()
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CreateQueue.html
        $response = new SimpleMockedResponse(<<<JSON
{
    "QueueUrl":"https://queue.amazonaws.com/123456789012/MyQueue"
}
JSON
        );

        $client = new MockHttpClient($response);
        $result = new CreateQueueResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals('https://queue.amazonaws.com/123456789012/MyQueue', $result->getQueueUrl());
    }
}
