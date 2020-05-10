<?php

namespace AsyncAws\EventBridge\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\EventBridge\EventBridgeClient;
use AsyncAws\EventBridge\Input\PutEventsRequest;
use AsyncAws\EventBridge\ValueObject\PutEventsRequestEntry;

class EventBridgeClientTest extends TestCase
{
    public function testPutEvents(): void
    {
        $client = $this->getClient();

        $input = new PutEventsRequest([
            'Entries' => [new PutEventsRequestEntry([
                'Time' => new \DateTimeImmutable(),
                'Source' => 'change me',
                'Resources' => ['change me'],
                'DetailType' => 'change me',
                'Detail' => 'change me',
                'EventBusName' => 'change me',
            ])],
        ]);
        $result = $client->PutEvents($input);

        $result->resolve();

        self::assertSame(1337, $result->getFailedEntryCount());
        // self::assertTODO(expected, $result->getEntries());
    }

    private function getClient(): EventBridgeClient
    {
        self::markTestSkipped('No docker image found..');

        return new EventBridgeClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
