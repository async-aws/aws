<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\InitiateAuthResponse;
use AsyncAws\CognitoIdentityProvider\ValueObject\AuthenticationResultType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class InitiateAuthResponseTest extends TestCase
{
    public function testInitiateAuthResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_InitiateAuth.html
        $response = new SimpleMockedResponse(json_encode([
            'AuthenticationResult' => [
                'AccessToken' => 'access-09',
                'ExpiresIn' => 67890,
                'IdToken' => 'my-token',
                'NewDeviceMetadata' => [
                    'DeviceGroupKey' => 'browser',
                    'DeviceKey' => 'zsh654',
                ],
                'RefreshToken' => 'refresh-me',
                'TokenType' => 'hash',
            ],
            'ChallengeName' => 'chall78',
            'ChallengeParameters' => ['custom' => 'parameter'],
            'Session' => 'session123',
        ]));

        $client = new MockHttpClient($response);
        $result = new InitiateAuthResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals(
            AuthenticationResultType::create(
                [
                    'AccessToken' => 'access-09',
                    'ExpiresIn' => 67890,
                    'IdToken' => 'my-token',
                    'NewDeviceMetadata' => [
                        'DeviceGroupKey' => 'browser',
                        'DeviceKey' => 'zsh654',
                    ],
                    'RefreshToken' => 'refresh-me',
                    'TokenType' => 'hash',
                ]
            ),
            $result->getAuthenticationResult()
        );
        self::assertSame('chall78', $result->getChallengeName());
        self::assertSame(['custom' => 'parameter'], $result->getChallengeParameters());
        self::assertSame('session123', $result->getSession());
    }
}
