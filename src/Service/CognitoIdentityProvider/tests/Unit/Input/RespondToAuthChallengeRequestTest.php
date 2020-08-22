<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\RespondToAuthChallengeRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Test\TestCase;

class RespondToAuthChallengeRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new RespondToAuthChallengeRequest([
            'ClientId' => 'id123',
            'ChallengeName' => 'CUSTOM_CHALLENGE',
            'Session' => 'session11',
            'ChallengeResponses' => ['SECRET_HASH' => 'qwe123'],
            'AnalyticsMetadata' => new AnalyticsMetadataType(['AnalyticsEndpointId' => 'foo123']),
            'UserContextData' => new UserContextDataType(['EncodedData' => 'Passw0rd!']),
            'ClientMetadata' => ['username' => 'stickman'],
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_RespondToAuthChallenge.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AWSCognitoIdentityProviderService.RespondToAuthChallenge

            {
               "ClientId": "id123",
               "ChallengeName": "CUSTOM_CHALLENGE",
               "Session": "session11",
               "ChallengeResponses": {
                  "SECRET_HASH" : "qwe123"
               },
               "AnalyticsMetadata": {
                  "AnalyticsEndpointId": "foo123"
               },
               "UserContextData": {
                  "EncodedData": "Passw0rd!"
               },
               "ClientMetadata": {
                  "username" : "stickman"
               }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
