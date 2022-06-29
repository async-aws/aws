<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingPrincipalsRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Result\ListThingPrincipalsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListThingPrincipalsResponseTest extends TestCase
{
    public function testListThingPrincipalsResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThingPrincipals.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingPrincipalsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingPrincipalsRequest([]));

        // self::assertTODO(expected, $result->getprincipals());
        self::assertSame('changeIt', $result->getnextToken());
    }
}
