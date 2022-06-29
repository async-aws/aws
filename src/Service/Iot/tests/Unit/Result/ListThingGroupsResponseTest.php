<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingGroupsRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Result\ListThingGroupsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListThingGroupsResponseTest extends TestCase
{
    public function testListThingGroupsResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThingGroups.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingGroupsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingGroupsRequest([]));

        // self::assertTODO(expected, $result->getthingGroups());
        self::assertSame('changeIt', $result->getnextToken());
    }
}
