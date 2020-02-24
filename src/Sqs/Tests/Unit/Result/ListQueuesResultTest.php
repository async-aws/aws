<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\ListQueuesResult;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class ListQueuesResultTest extends TestCase
{
    public function testListQueuesResult()
    {
        $response = new SimpleMockedResponse(<<<XML
<ListQueuesResponse>
    <ListQueuesResult>
        <QueueUrl>https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue</QueueUrl>
    </ListQueuesResult>
    <ResponseMetadata>
        <RequestId>725275ae-0b9b-4762-b238-436d7c65a1ac</RequestId>
    </ResponseMetadata>
</ListQueuesResponse>
XML
        );

        $client = new MockHttpClient($response);
        $result = new ListQueuesResult($client->request('POST', 'http://localhost'), $client);

        self::assertContains('https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue', $result->getQueueUrls());
        self::assertContains('https://sqs.us-east-2.amazonaws.com/123456789012/MyQueue', $result);
    }
}
