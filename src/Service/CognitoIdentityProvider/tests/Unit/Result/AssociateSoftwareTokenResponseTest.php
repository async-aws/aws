<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\AssociateSoftwareTokenResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AssociateSoftwareTokenResponseTest extends TestCase
{
    public function testAssociateSoftwareTokenResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AssociateSoftwareToken.html
        $response = new SimpleMockedResponse('{
            "SecretCode": "SWxGBHyDUuLtv6s0WHKAU1N7kETzPehv",
            "Session": "B85A977AE91F811E8B1577CCA22C8"
        }');

        $client = new MockHttpClient($response);
        $result = new AssociateSoftwareTokenResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('SWxGBHyDUuLtv6s0WHKAU1N7kETzPehv', $result->getSecretCode());
        self::assertSame('B85A977AE91F811E8B1577CCA22C8', $result->getSession());
    }
}
