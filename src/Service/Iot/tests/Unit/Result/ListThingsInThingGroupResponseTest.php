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
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingsInThingGroup.html
        $response = new SimpleMockedResponse('{
            "things": [ "hvac1", "light1", "sensor1" ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingsInThingGroupResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingsInThingGroupRequest(['thingGroupName' => 'unit1']));

        self::assertEquals(['hvac1', 'light1', 'sensor1'], iterator_to_array($result->getThings()));
        self::assertSame(null, $result->getNextToken());
    }
}
