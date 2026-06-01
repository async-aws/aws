<?php

namespace AsyncAws\SsoOidc\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Result\RegisterClientResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class RegisterClientResponseTest extends TestCase
{
    public function testRegisterClientResponse(): void
    {
        self::fail('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "clientId": "_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID",
            "clientIdIssuedAt": 1579725929,
            "clientSecret": "VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0",
            "clientSecretExpiresAt": 1587584729
        }');

        $client = new MockHttpClient($response);
        $result = new RegisterClientResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getClientId());
        self::assertSame('changeIt', $result->getClientSecret());
        self::assertSame(1337, $result->getClientIdIssuedAt());
        self::assertSame(1337, $result->getClientSecretExpiresAt());
        self::assertSame('changeIt', $result->getAuthorizationEndpoint());
        self::assertSame('changeIt', $result->getTokenEndpoint());
    }
}
