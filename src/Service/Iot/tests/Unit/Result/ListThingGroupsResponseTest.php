<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingGroupsRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Result\ListThingGroupsResponse;
use AsyncAws\Iot\ValueObject\GroupNameAndArn;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListThingGroupsResponseTest extends TestCase
{
    public function testListThingGroupsResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingGroups.html
        $response = new SimpleMockedResponse('{
            "thingGroups": [
                {
                    "groupArn": "unit1:arn",
                    "groupName": "unit1"
                },
                {
                    "groupArn": "building1:arn",
                    "groupName": "building1"
                },
                {
                    "groupArn": "unit2:arn",
                    "groupName": "unit2"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingGroupsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingGroupsRequest([]));

        $expected = [
            new GroupNameAndArn(['groupArn' => 'unit1:arn', 'groupName' => 'unit1']),
            new GroupNameAndArn(['groupArn' => 'building1:arn', 'groupName' => 'building1']),
            new GroupNameAndArn(['groupArn' => 'unit2:arn', 'groupName' => 'unit2']),
        ];
        self::assertEquals($expected, iterator_to_array($result->getThingGroups()));
        self::assertSame(null, $result->getNextToken());
    }
}
