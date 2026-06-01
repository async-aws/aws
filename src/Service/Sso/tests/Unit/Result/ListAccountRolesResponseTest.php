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
        // see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_ListAccountRoles.html
        $response = new SimpleMockedResponse('{
            "nextToken": "eyJuZXh0VG9rZW4iOm51bGx9",
            "roleList": [
                {
                    "roleName": "AdministratorAccess",
                    "accountId": "123456789011"
                },
                {
                    "roleName": "ReadOnlyAccess",
                    "accountId": "123456789011"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListAccountRolesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SsoClient(), new ListAccountRolesRequest([]));

        self::assertSame('eyJuZXh0VG9rZW4iOm51bGx9', $result->getNextToken());

        $roleList = iterator_to_array($result->getRoleList(true));
        self::assertCount(2, $roleList);
        self::assertSame('AdministratorAccess', $roleList[0]->getRoleName());
        self::assertSame('123456789011', $roleList[0]->getAccountId());
        self::assertSame('ReadOnlyAccess', $roleList[1]->getRoleName());
        self::assertSame('123456789011', $roleList[1]->getAccountId());
    }
}
