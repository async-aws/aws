<?php

namespace AsyncAws\EventBridge\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
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
                'Source' => 'com.mycompany.myapp',
                'Detail' => '{"key1": "value1","key2": "value2"}',
                'Resources' => [
                    'resource1',
                    'resource2',
                ],
                'DetailType' => 'myDetailType',
            ])],
        ]);
        $result = $client->PutEvents($input);

        self::assertCount(1, $result->getEntries());
    }

    private function getClient(): EventBridgeClient
    {
        return new EventBridgeClient([
            'endpoint' => 'http://localhost:4571',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
