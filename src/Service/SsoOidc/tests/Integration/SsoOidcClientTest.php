<?php

namespace AsyncAws\SsoOidc\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Input\CreateTokenRequest;
use AsyncAws\SsoOidc\SsoOidcClient;

class SsoOidcClientTest extends TestCase
{
    public function testCreateToken(): void
    {
        $client = $this->getClient();

        $input = new CreateTokenRequest([
            'clientId' => 'change me',
            'clientSecret' => 'change me',
            'grantType' => 'change me',
            'deviceCode' => 'change me',
            'code' => 'change me',
            'refreshToken' => 'change me',
            'scope' => ['change me'],
            'redirectUri' => 'change me',
            'codeVerifier' => 'change me',
        ]);
        $result = $client->createToken($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getAccessToken());
        self::assertSame('changeIt', $result->getTokenType());
        self::assertSame(1337, $result->getExpiresIn());
        self::assertSame('changeIt', $result->getRefreshToken());
        self::assertSame('changeIt', $result->getIdToken());
    }

    private function getClient(): SsoOidcClient
    {
        self::markTestSkipped('There is no image available for a SSO provider mock.');

        return new SsoOidcClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
