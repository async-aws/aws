<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Result\CreateThingGroupResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateThingGroupResponseTest extends TestCase
{
    public function testCreateThingGroupResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThingGroup.html
        $response = new SimpleMockedResponse('{
            "thingGroupArn": "unit1-arn",
            "thingGroupId": "29278982HJS",
            "thingGroupName": "unit1"
        }');

        $client = new MockHttpClient($response);
        $result = new CreateThingGroupResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('unit1', $result->getThingGroupName());
        self::assertSame('unit1-arn', $result->getThingGroupArn());
        self::assertSame('29278982HJS', $result->getThingGroupId());
    }
}
