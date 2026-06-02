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
            'roleName' => 'AdministratorAccess',
            'accountId' => '123456789011',
            'accessToken' => 'eyJlbmMiOiJBMjU2R0NNIn0EXAMPLEACCESSTOKEN',
        ]);
        $result = $client->getRoleCredentials($input);

        $result->resolve();
        // self::assertTODO(expected, $result->getRoleCredentials());
    }

    public function testListAccountRoles(): void
    {
        $client = $this->getClient();

        $input = new ListAccountRolesRequest([
            'nextToken' => 'eyJuZXh0VG9rZW4iOm51bGx9',
            'maxResults' => 50,
            'accessToken' => 'eyJlbmMiOiJBMjU2R0NNIn0EXAMPLEACCESSTOKEN',
            'accountId' => '123456789011',
        ]);
        $result = $client->listAccountRoles($input);

        $result->resolve();

        self::assertSame('eyJuZXh0VG9rZW4iOm51bGx9', $result->getNextToken());
        // self::assertTODO(expected, $result->getRoleList());
    }

    public function testListAccounts(): void
    {
        $client = $this->getClient();

        $input = new ListAccountsRequest([
            'nextToken' => 'eyJuZXh0VG9rZW4iOm51bGx9',
            'maxResults' => 25,
            'accessToken' => 'eyJlbmMiOiJBMjU2R0NNIn0EXAMPLEACCESSTOKEN',
        ]);
        $result = $client->listAccounts($input);

        $result->resolve();

        self::assertSame('eyJuZXh0VG9rZW4iOm51bGx9', $result->getNextToken());
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
