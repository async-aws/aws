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
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/eventbridge/latest/APIReference/API_PutEvents.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new PutEventsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(1337, $result->getFailedEntryCount());
        // self::assertTODO(expected, $result->getEntries());
    }
}
