<?php

namespace AsyncAws\SsoOidc\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Input\CreateTokenRequest;
use AsyncAws\SsoOidc\Input\RegisterClientRequest;
use AsyncAws\SsoOidc\Input\StartDeviceAuthorizationRequest;
use AsyncAws\SsoOidc\Result\CreateTokenResponse;
use AsyncAws\SsoOidc\Result\RegisterClientResponse;
use AsyncAws\SsoOidc\Result\StartDeviceAuthorizationResponse;
use AsyncAws\SsoOidc\SsoOidcClient;
use Symfony\Component\HttpClient\MockHttpClient;

class SsoOidcClientTest extends TestCase
{
    public function testCreateToken(): void
    {
        $client = new SsoOidcClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateTokenRequest([
            'clientId' => 'change me',
            'clientSecret' => 'change me',
            'grantType' => 'change me',
        ]);
        $result = $client->createToken($input);

        self::assertInstanceOf(CreateTokenResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testRegisterClient(): void
    {
        $client = new SsoOidcClient([], new NullProvider(), new MockHttpClient());

        $input = new RegisterClientRequest([
            'clientName' => 'change me',
            'clientType' => 'change me',
        ]);
        $result = $client->registerClient($input);

        self::assertInstanceOf(RegisterClientResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartDeviceAuthorization(): void
    {
        $client = new SsoOidcClient([], new NullProvider(), new MockHttpClient());

        $input = new StartDeviceAuthorizationRequest([
            'clientId' => 'change me',
            'clientSecret' => 'change me',
            'startUrl' => 'change me',
        ]);
        $result = $client->startDeviceAuthorization($input);

        self::assertInstanceOf(StartDeviceAuthorizationResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
