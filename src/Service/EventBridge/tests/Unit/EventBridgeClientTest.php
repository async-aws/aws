<?php

namespace AsyncAws\EventBridge\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\EventBridge\EventBridgeClient;
use AsyncAws\EventBridge\Input\PutEventsRequest;
use AsyncAws\EventBridge\Result\PutEventsResponse;
use AsyncAws\EventBridge\ValueObject\PutEventsRequestEntry;
use Symfony\Component\HttpClient\MockHttpClient;

class EventBridgeClientTest extends TestCase
{
    public function testPutEvents(): void
    {
        $client = new EventBridgeClient([], new NullProvider(), new MockHttpClient());

        $input = new PutEventsRequest([
            'Entries' => [new PutEventsRequestEntry([

            ])],
        ]);
        $result = $client->PutEvents($input);

        self::assertInstanceOf(PutEventsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
