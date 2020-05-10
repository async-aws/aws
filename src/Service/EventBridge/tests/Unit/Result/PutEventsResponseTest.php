<?php

namespace AsyncAws\EventBridge\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\EventBridge\Result\PutEventsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutEventsResponseTest extends TestCase
{
    public function testPutEventsResponse(): void
    {
        // see https://docs.aws.amazon.com/eventbridge/latest/APIReference/API_PutEvents.html
        $response = new SimpleMockedResponse('{
"FailedEntryCount": 1337,
"Entries": [
    {
        "EventId": "11710aed-b79e-4468-a20b-bb3c0c3b4860"
    },
    {
        "EventId": "d804d26a-88db-4b66-9eaf-9a11c708ae82"
    }
]
}');

        $client = new MockHttpClient($response);
        $result = new PutEventsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(1337, $result->getFailedEntryCount());
        $entries = $result->getEntries();
        self::assertCount(2, $entries);
        self::assertSame('11710aed-b79e-4468-a20b-bb3c0c3b4860', $entries[0]->getEventId());
        self::assertSame('d804d26a-88db-4b66-9eaf-9a11c708ae82', $entries[1]->getEventId());
    }
}
