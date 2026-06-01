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
            'clientId' => '_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID',
            'clientSecret' => 'VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0',
            'grantType' => 'urn:ietf:params:oauth:grant-type:device_code',
        ]);
        $result = $client->createToken($input);

        self::assertInstanceOf(CreateTokenResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testRegisterClient(): void
    {
        $client = new SsoOidcClient([], new NullProvider(), new MockHttpClient());

        $input = new RegisterClientRequest([
            'clientName' => 'My IDE Plugin',
            'clientType' => 'public',
        ]);
        $result = $client->registerClient($input);

        self::assertInstanceOf(RegisterClientResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartDeviceAuthorization(): void
    {
        $client = new SsoOidcClient([], new NullProvider(), new MockHttpClient());

        $input = new StartDeviceAuthorizationRequest([
            'clientId' => '_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID',
            'clientSecret' => 'VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0',
            'startUrl' => 'https://identitycenter.amazonaws.com/ssoins-111111111111',
        ]);
        $result = $client->startDeviceAuthorization($input);

        self::assertInstanceOf(StartDeviceAuthorizationResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
