<?php

namespace AsyncAws\IotData\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\IotData\Input\UpdateThingShadowRequest;
use AsyncAws\IotData\IotDataClient;

class IotDataClientTest extends TestCase
{
    public function testUpdateThingShadow(): void
    {
        $client = $this->getClient();

        $input = new UpdateThingShadowRequest([
            'thingName' => 'unit12:hvac',
            'payload' => json_encode(['state' => ['reported' => ['temperature' => 21]]]),
        ]);
        $result = $client->updateThingShadow($input);

        $result->resolve();
        self::assertEquals(json_encode(['state' => ['reported' => ['temperature' => 21]]]), $result->getPayload());
    }

    private function getClient(): IotDataClient
    {
        self::markTestSkipped('There is no free docker image available for IotData.');

        return new IotDataClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
