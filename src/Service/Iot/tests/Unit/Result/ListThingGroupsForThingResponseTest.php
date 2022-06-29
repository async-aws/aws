<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingGroupsForThingRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Result\ListThingGroupsForThingResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListThingGroupsForThingResponseTest extends TestCase
{
    public function testListThingGroupsForThingResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThingGroupsForThing.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingGroupsForThingResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingGroupsForThingRequest([]));

        // self::assertTODO(expected, $result->getthingGroups());
        self::assertSame('changeIt', $result->getnextToken());
    }
}
