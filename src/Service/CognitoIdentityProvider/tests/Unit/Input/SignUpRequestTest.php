<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\SignUpRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Test\TestCase;

class SignUpRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SignUpRequest([
            'ClientId' => 'id132',
            'SecretHash' => 'signUpSecretHash',
            'Username' => 'stickman',
            'Password' => 'Passw0rd!',
            'UserAttributes' => [new AttributeType(['Name' => 'internalId', 'Value' => 'abc987'])],
            'ValidationData' => [new AttributeType(['Name' => 'isValid', 'Value' => 'YES'])],
            'AnalyticsMetadata' => new AnalyticsMetadataType(['AnalyticsEndpointId' => 'qwe456']),
            'UserContextData' => new UserContextDataType(['EncodedData' => 's3creeT']),
            'ClientMetadata' => ['lastName' => 'Lennon'],
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_SignUp.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AWSCognitoIdentityProviderService.SignUp
            Accept: application/json

            {
               "AnalyticsMetadata": {
                  "AnalyticsEndpointId": "qwe456"
               },
               "ClientId": "id132",
               "ClientMetadata": {
                  "lastName" : "Lennon"
               },
               "Password": "Passw0rd!",
               "SecretHash": "signUpSecretHash",
               "UserAttributes": [
                  {
                     "Name": "internalId",
                     "Value": "abc987"
                  }
               ],
               "UserContextData": {
                  "EncodedData": "s3creeT"
               },
               "Username": "stickman",
               "ValidationData": [
                  {
                     "Name": "isValid",
                     "Value": "YES"
                  }
               ]
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
