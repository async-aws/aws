<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\RespondToAuthChallengeResponse;
use AsyncAws\CognitoIdentityProvider\ValueObject\AuthenticationResultType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class RespondToAuthChallengeResponseTest extends TestCase
{
    public function testRespondToAuthChallengeResponse(): void
    {
        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_RespondToAuthChallenge.html
        $response = new SimpleMockedResponse(\json_encode([
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
            'ChallengeName' => 'CUSTOM_CHALLENGE',
            'ChallengeParameters' => ['custom' => 'parameter'],
            'Session' => 'session123',
        ]));

        $client = new MockHttpClient($response);
        $result = new RespondToAuthChallengeResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

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
        self::assertSame('CUSTOM_CHALLENGE', $result->getChallengeName());
        self::assertSame(['custom' => 'parameter'], $result->getChallengeParameters());
        self::assertSame('session123', $result->getSession());
    }
}
