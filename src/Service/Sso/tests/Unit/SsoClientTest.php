<?php

namespace AsyncAws\Sso\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\GetRoleCredentialsRequest;
use AsyncAws\Sso\Input\ListAccountRolesRequest;
use AsyncAws\Sso\Input\ListAccountsRequest;
use AsyncAws\Sso\Result\GetRoleCredentialsResponse;
use AsyncAws\Sso\Result\ListAccountRolesResponse;
use AsyncAws\Sso\Result\ListAccountsResponse;
use AsyncAws\Sso\SsoClient;
use Symfony\Component\HttpClient\MockHttpClient;

class SsoClientTest extends TestCase
{
    public function testGetRoleCredentials(): void
    {
        $client = new SsoClient([], new NullProvider(), new MockHttpClient());

        $input = new GetRoleCredentialsRequest([
            'roleName' => 'RoleName',
            'accountId' => 'AccountId',
            'accessToken' => 'AccessToken',
        ]);
        $result = $client->getRoleCredentials($input);

        self::assertInstanceOf(GetRoleCredentialsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListAccountRoles(): void
    {
        $client = new SsoClient([], new NullProvider(), new MockHttpClient());

        $input = new ListAccountRolesRequest([
            'accessToken' => 'eyJlbmMiOiJBMjU2R0NNIn0EXAMPLEACCESSTOKEN',
            'accountId' => '123456789011',
        ]);
        $result = $client->listAccountRoles($input);

        self::assertInstanceOf(ListAccountRolesResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListAccounts(): void
    {
        $client = new SsoClient([], new NullProvider(), new MockHttpClient());

        $input = new ListAccountsRequest([
            'accessToken' => 'eyJlbmMiOiJBMjU2R0NNIn0EXAMPLEACCESSTOKEN',
        ]);
        $result = $client->listAccounts($input);

        self::assertInstanceOf(ListAccountsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
