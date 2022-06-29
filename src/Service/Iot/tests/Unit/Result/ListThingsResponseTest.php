<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingsRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Result\ListThingsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListThingsResponseTest extends TestCase
{
    public function testListThingsResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThings.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingsRequest([]));

        // self::assertTODO(expected, $result->getthings());
        self::assertSame('changeIt', $result->getnextToken());
    }
}
