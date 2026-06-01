<?php

namespace AsyncAws\SsoOidc\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Input\CreateTokenRequest;
use AsyncAws\SsoOidc\Input\RegisterClientRequest;
use AsyncAws\SsoOidc\Input\StartDeviceAuthorizationRequest;
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

    public function testRegisterClient(): void
    {
        $client = $this->getClient();

        $input = new RegisterClientRequest([
            'clientName' => 'change me',
            'clientType' => 'change me',
            'scopes' => ['change me'],
            'redirectUris' => ['change me'],
            'grantTypes' => ['change me'],
            'issuerUrl' => 'change me',
            'entitledApplicationArn' => 'change me',
        ]);
        $result = $client->registerClient($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getClientId());
        self::assertSame('changeIt', $result->getClientSecret());
        self::assertSame(1337, $result->getClientIdIssuedAt());
        self::assertSame(1337, $result->getClientSecretExpiresAt());
        self::assertSame('changeIt', $result->getAuthorizationEndpoint());
        self::assertSame('changeIt', $result->getTokenEndpoint());
    }

    public function testStartDeviceAuthorization(): void
    {
        $client = $this->getClient();

        $input = new StartDeviceAuthorizationRequest([
            'clientId' => 'change me',
            'clientSecret' => 'change me',
            'startUrl' => 'change me',
        ]);
        $result = $client->startDeviceAuthorization($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getDeviceCode());
        self::assertSame('changeIt', $result->getUserCode());
        self::assertSame('changeIt', $result->getVerificationUri());
        self::assertSame('changeIt', $result->getVerificationUriComplete());
        self::assertSame(1337, $result->getExpiresIn());
        self::assertSame(1337, $result->getInterval());
    }

    private function getClient(): SsoOidcClient
    {
        self::markTestSkipped('There is no image available for a SSO provider mock.');

        return new SsoOidcClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
