<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\RevokeTokenResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class RevokeTokenResponseTest extends TestCase
{
    public function testRevokeTokenResponse(): void
    {
        self::markTestSkipped('The response has no body to test');

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_RevokeToken.html
        $response = new SimpleMockedResponse('{}');

        $client = new MockHttpClient($response);
        $result = new RevokeTokenResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
    }
}
