<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\InitiateAuthRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Test\TestCase;

class InitiateAuthRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new InitiateAuthRequest([
            'AuthFlow' => 'USER_PASSWORD_AUTH',
            'AuthParameters' => ['username' => 'stickman'],
            'ClientMetadata' => ['lastName' => 'Lennon'],
            'ClientId' => 'abc123',
            'AnalyticsMetadata' => new AnalyticsMetadataType(['AnalyticsEndpointId' => 'id909']),
            'UserContextData' => new UserContextDataType(['EncodedData' => 'Passw0rd!']),
        ]);

        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_InitiateAuth.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AWSCognitoIdentityProviderService.InitiateAuth

            {
               "AnalyticsMetadata": {
                  "AnalyticsEndpointId": "id909"
               },
               "AuthFlow": "USER_PASSWORD_AUTH",
               "AuthParameters": {
                  "username" : "stickman"
               },
               "ClientId": "abc123",
               "ClientMetadata": {
                  "lastName" : "Lennon"
               },
               "UserContextData": {
                  "EncodedData": "Passw0rd!"
               }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
