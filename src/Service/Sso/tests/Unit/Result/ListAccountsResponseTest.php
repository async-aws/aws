<?php

namespace AsyncAws\Sso\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\ListAccountsRequest;
use AsyncAws\Sso\Result\ListAccountsResponse;
use AsyncAws\Sso\SsoClient;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListAccountsResponseTest extends TestCase
{
    public function testListAccountsResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_ListAccounts.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListAccountsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SsoClient(), new ListAccountsRequest([]));

        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getAccountList());
    }
}
