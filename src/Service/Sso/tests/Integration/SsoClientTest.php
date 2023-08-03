<?php

namespace AsyncAws\Sso\Tests\Integration;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\GetRoleCredentialsRequest;
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

    private function getClient(): SsoClient
    {
        self::markTestSkipped('There is no image available for a SSO provider mock.');

        return new SsoClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
