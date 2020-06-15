<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\ResendConfirmationCodeRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Test\TestCase;

class ResendConfirmationCodeRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ResendConfirmationCodeRequest([
            'ClientId' => 'cId123',
            'SecretHash' => 'S3cre1!',
            'UserContextData' => new UserContextDataType(['EncodedData' => 'Passw0rd!']),
            'Username' => 'stickman',
            'AnalyticsMetadata' => new AnalyticsMetadataType(['AnalyticsEndpointId' => 'id3909']),
            'ClientMetadata' => ['lastName' => 'Lennon'],
        ]);

        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_ResendConfirmationCode.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AWSCognitoIdentityProviderService.ResendConfirmationCode

            {
               "ClientId": "cId123",
               "SecretHash": "S3cre1!",
               "UserContextData": {
                  "EncodedData": "Passw0rd!"
               },
               "Username": "stickman",
               "AnalyticsMetadata": {
                  "AnalyticsEndpointId": "id3909"
               },
               "ClientMetadata": {
                  "lastName" : "Lennon"
               }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
