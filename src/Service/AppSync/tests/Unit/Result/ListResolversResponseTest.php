<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Result\ListResolversResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListResolversResponseTest extends TestCase
{
    public function testListResolversResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_ListResolvers.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListResolversResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        // self::assertTODO(expected, $result->getresolvers());
        self::assertSame('changeIt', $result->getnextToken());
    }
}
