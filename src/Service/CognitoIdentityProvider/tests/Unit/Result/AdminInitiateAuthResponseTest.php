<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Enum\ChallengeNameType;
use AsyncAws\CognitoIdentityProvider\Result\AdminInitiateAuthResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AdminInitiateAuthResponseTest extends TestCase
{
    public function testAdminInitiateAuthResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminInitiateAuth.html
        $response = new SimpleMockedResponse('{
           "AuthenticationResult": {
              "AccessToken": "123456789",
              "ExpiresIn": 20,
              "IdToken": "string",
              "NewDeviceMetadata": {
                 "DeviceGroupKey": "string",
                 "DeviceKey": "string"
              },
              "RefreshToken": "string",
              "TokenType": "string"
           },
           "ChallengeName": "MFA_SETUP",
           "ChallengeParameters": {
              "key" : "value"
           },
           "Session": "123456789"
        }');

        $client = new MockHttpClient($response);
        $result = new AdminInitiateAuthResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(ChallengeNameType::MFA_SETUP, $result->getChallengeName());
        self::assertSame('123456789', $result->getSession());
        self::assertSame(20, $result->getAuthenticationResult()->getExpiresIn());
    }
}
