<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingGroupsForThingRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Result\ListThingGroupsForThingResponse;
use AsyncAws\Iot\ValueObject\GroupNameAndArn;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListThingGroupsForThingResponseTest extends TestCase
{
    public function testListThingGroupsForThingResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingGroupsForThing.html
        $response = new SimpleMockedResponse('{
            "thingGroups": [
                {
                    "groupArn": "unit1:arn",
                    "groupName": "unit1"
                },
                {
                    "groupArn": "building1:arn",
                    "groupName": "building1"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingGroupsForThingResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingGroupsForThingRequest(['thingName' => 'hvac1']));
        $expected = [
            new GroupNameAndArn(['groupArn' => 'unit1:arn', 'groupName' => 'unit1']),
            new GroupNameAndArn(['groupArn' => 'building1:arn', 'groupName' => 'building1']),
        ];
        self::assertEquals($expected, iterator_to_array($result->getThingGroups()));
        self::assertSame(null, $result->getNextToken());
    }
}
