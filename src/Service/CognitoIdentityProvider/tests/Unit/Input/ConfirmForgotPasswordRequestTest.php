<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\ConfirmForgotPasswordRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Test\TestCase;

class ConfirmForgotPasswordRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ConfirmForgotPasswordRequest([
            'ClientId' => 'id909',
            'SecretHash' => 'h4Sh!',
            'Username' => 'stickman',
            'ConfirmationCode' => 'confirm-123',
            'Password' => 'Passw0rd!',
            'AnalyticsMetadata' => new AnalyticsMetadataType(['AnalyticsEndpointId' => 'e123']),
            'UserContextData' => new UserContextDataType(['EncodedData' => 'd4t4']),
            'ClientMetadata' => ['lastName' => 'Lennon'],
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ConfirmForgotPassword.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AWSCognitoIdentityProviderService.ConfirmForgotPassword
            Accept: application/json

            {
               "ClientId": "id909",
               "SecretHash": "h4Sh!",
               "Username": "stickman",
               "ConfirmationCode": "confirm-123",
               "Password": "Passw0rd!",
               "AnalyticsMetadata": {
                  "AnalyticsEndpointId": "e123"
               },
               "UserContextData": {
                  "EncodedData": "d4t4"
               },
               "ClientMetadata": {
                  "lastName" : "Lennon"
               }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
