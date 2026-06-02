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
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "clientId": "_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID",
            "clientIdIssuedAt": 1579725929,
            "clientSecret": "VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0",
            "clientSecretExpiresAt": 1587584729
        }');

        $client = new MockHttpClient($response);
        $result = new RegisterClientResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID', $result->getClientId());
        self::assertSame('VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0', $result->getClientSecret());
        self::assertSame(1579725929, $result->getClientIdIssuedAt());
        self::assertSame(1587584729, $result->getClientSecretExpiresAt());
        self::assertNull($result->getAuthorizationEndpoint());
        self::assertNull($result->getTokenEndpoint());
    }
}
