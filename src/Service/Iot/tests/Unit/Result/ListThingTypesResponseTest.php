<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingTypesRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Result\ListThingTypesResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListThingTypesResponseTest extends TestCase
{
    public function testListThingTypesResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThingTypes.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingTypesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingTypesRequest([]));

        // self::assertTODO(expected, $result->getthingTypes());
        self::assertSame('changeIt', $result->getnextToken());
    }
}
