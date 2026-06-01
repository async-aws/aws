<?php

namespace AsyncAws\Sso\Tests\Integration;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\GetRoleCredentialsRequest;
use AsyncAws\Sso\Input\ListAccountRolesRequest;
use AsyncAws\Sso\Input\ListAccountsRequest;
use AsyncAws\Sso\SsoClient;

class SsoClientTest extends TestCase
{
    public function testGetRoleCredentials(): void
    {
        $client = $this->getClient();

        $input = new GetRoleCredentialsRequest([
            'roleName' => 'change me',
            'accountId' => 'change me',
            'accessToken' => 'change me',
        ]);
        $result = $client->getRoleCredentials($input);

        $result->resolve();
        // self::assertTODO(expected, $result->getRoleCredentials());
    }

    public function testListAccountRoles(): void
    {
        $client = $this->getClient();

        $input = new ListAccountRolesRequest([
            'nextToken' => 'change me',
            'maxResults' => 1337,
            'accessToken' => 'change me',
            'accountId' => 'change me',
        ]);
        $result = $client->listAccountRoles($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getRoleList());
    }

    public function testListAccounts(): void
    {
        $client = $this->getClient();

        $input = new ListAccountsRequest([
            'nextToken' => 'change me',
            'maxResults' => 1337,
            'accessToken' => 'change me',
        ]);
        $result = $client->listAccounts($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getAccountList());
    }

    private function getClient(): SsoClient
    {
        self::markTestSkipped('There is no image available for a SSO provider mock.');

        return new SsoClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
