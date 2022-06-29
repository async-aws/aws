<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Result\AddThingToThingGroupResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AddThingToThingGroupResponseTest extends TestCase
{
    public function testAddThingToThingGroupResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_AddThingToThingGroup.html
        $response = new SimpleMockedResponse('{
        }');

        $client = new MockHttpClient($response);
        $result = new AddThingToThingGroupResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
        self::assertTrue($result->resolve());
    }
}
