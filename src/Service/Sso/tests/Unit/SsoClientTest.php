<?php

namespace AsyncAws\Sso\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\GetRoleCredentialsRequest;
use AsyncAws\Sso\Result\GetRoleCredentialsResponse;
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
}
