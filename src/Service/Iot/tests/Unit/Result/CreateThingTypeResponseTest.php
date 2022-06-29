<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Result\CreateThingTypeResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateThingTypeResponseTest extends TestCase
{
    public function testCreateThingTypeResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThingType.html
        $response = new SimpleMockedResponse('{
            "thingTypeArn": "hvac:arn",
            "thingTypeId": "lkjdflksd878",
            "thingTypeName": "hvac"
        }');

        $client = new MockHttpClient($response);
        $result = new CreateThingTypeResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('hvac', $result->getthingTypeName());
        self::assertSame('hvac:arn', $result->getthingTypeArn());
        self::assertSame('lkjdflksd878', $result->getthingTypeId());
    }
}
