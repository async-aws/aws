<?php

namespace AsyncAws\SsoOidc\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Input\CreateTokenRequest;
use AsyncAws\SsoOidc\Result\CreateTokenResponse;
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
}
