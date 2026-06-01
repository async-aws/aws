<?php

namespace AsyncAws\Sso\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\ListAccountRolesRequest;
use AsyncAws\Sso\Result\ListAccountRolesResponse;
use AsyncAws\Sso\SsoClient;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListAccountRolesResponseTest extends TestCase
{
    public function testListAccountRolesResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_ListAccountRoles.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListAccountRolesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SsoClient(), new ListAccountRolesRequest([]));

        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getRoleList());
    }
}
