<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\ForgotPasswordRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Test\TestCase;

class ForgotPasswordRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ForgotPasswordRequest([
            'ClientId' => 'client123',
            'SecretHash' => 'S3cret!',
            'UserContextData' => new UserContextDataType(['EncodedData' => 'Passw0rd!']),
            'Username' => 'stickman',
            'AnalyticsMetadata' => new AnalyticsMetadataType(['AnalyticsEndpointId' => 'id909']),
            'ClientMetadata' => ['lastName' => 'Lennon'],
        ]);

        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_ForgotPassword.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AWSCognitoIdentityProviderService.ForgotPassword

            {
               "ClientId": "client123",
               "SecretHash": "S3cret!",
               "UserContextData": {
                  "EncodedData": "Passw0rd!"
               },
               "Username": "stickman",
               "AnalyticsMetadata": {
                  "AnalyticsEndpointId": "id909"
               },
               "ClientMetadata": {
                  "lastName" : "Lennon"
               }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
