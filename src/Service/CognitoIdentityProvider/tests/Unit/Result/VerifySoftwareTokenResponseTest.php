<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\VerifySoftwareTokenResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class VerifySoftwareTokenResponseTest extends TestCase
{
    public function testVerifySoftwareTokenResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_VerifySoftwareToken.html
        $response = new SimpleMockedResponse('{
            "Session": "F7358A38BFF27816416D7846FC1EE",
            "Status": "SUCCESS"
        }');

        $client = new MockHttpClient($response);
        $result = new VerifySoftwareTokenResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('SUCCESS', $result->getStatus());
        self::assertSame('F7358A38BFF27816416D7846FC1EE', $result->getSession());
    }
}
