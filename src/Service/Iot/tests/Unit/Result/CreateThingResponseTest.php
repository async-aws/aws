<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Result\CreateThingResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateThingResponseTest extends TestCase
{
    public function testCreateThingResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThing.html
        $response = new SimpleMockedResponse('{
            "thingArn": "hvac1:arn",
            "thingId": "76786QJSFQ",
            "thingName": "hvac1"
        }');

        $client = new MockHttpClient($response);
        $result = new CreateThingResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('hvac1', $result->getThingName());
        self::assertSame('hvac1:arn', $result->getThingArn());
        self::assertSame('76786QJSFQ', $result->getThingId());
    }
}
