<?php

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\ListDeadLetterSourceQueuesRequest;
use AsyncAws\Sqs\Result\ListDeadLetterSourceQueuesResult;
use AsyncAws\Sqs\SqsClient;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListDeadLetterSourceQueuesResultTest extends TestCase
{
    public function testListDeadLetterSourceQueuesResult(): void
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ListDeadLetterSourceQueues.html
        $response = new SimpleMockedResponse(<<<JSON
        {
            "queueUrls": [
                "https://sqs.us-east-1.amazonaws.com/123456789012/MySourceQueue"
            ]
        }
        JSON);

        $client = new MockHttpClient($response);
        $result = new ListDeadLetterSourceQueuesResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SqsClient(), new ListDeadLetterSourceQueuesRequest([]));

        $queueUrls = iterator_to_array($result->getQueueUrls());
        self::assertSame('https://sqs.us-east-1.amazonaws.com/123456789012/MySourceQueue', $queueUrls[0]);
    }
}
