<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingsInThingGroupRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Result\ListThingsInThingGroupResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListThingsInThingGroupResponseTest extends TestCase
{
    public function testListThingsInThingGroupResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThingsInThingGroup.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingsInThingGroupResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingsInThingGroupRequest([]));

        // self::assertTODO(expected, $result->getthings());
        self::assertSame('changeIt', $result->getnextToken());
    }
}
