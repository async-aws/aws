<?php

namespace AsyncAws\IotData\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\IotData\Input\UpdateThingShadowRequest;
use AsyncAws\IotData\IotDataClient;
use AsyncAws\IotData\Result\UpdateThingShadowResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class IotDataClientTest extends TestCase
{
    public function testUpdateThingShadow(): void
    {
        $client = new IotDataClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateThingShadowRequest([
            'thingName' => 'unit1:hvac',
            'payload' => json_encode(['state' => ['reported' => ['temperature' => 19]]]),
        ]);
        $result = $client->updateThingShadow($input);

        self::assertInstanceOf(UpdateThingShadowResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
