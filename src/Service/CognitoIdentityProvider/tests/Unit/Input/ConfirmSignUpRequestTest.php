<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\ConfirmSignUpRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Test\TestCase;

class ConfirmSignUpRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ConfirmSignUpRequest([
            'ClientId' => 'client_12345',
            'SecretHash' => 'change me',
            'Username' => 'user@example.com',
            'ConfirmationCode' => '789123',
            'ForceAliasCreation' => false,
            'AnalyticsMetadata' => new AnalyticsMetadataType([
                'AnalyticsEndpointId' => 'change me',
            ]),
            'UserContextData' => new UserContextDataType([
                'EncodedData' => 'change me',
            ]),
            'ClientMetadata' => ['lastName' => 'Smith'],
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ConfirmSignUp.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
                "ClientId": "client_12345",
                "SecretHash": "change me",
                "Username": "user@example.com",
                "ConfirmationCode": "789123",
                "ForceAliasCreation": false,
                "AnalyticsMetadata": {
                    "AnalyticsEndpointId": "change me"
                },
                "UserContextData": {
                    "EncodedData": "change me"
                },
                "ClientMetadata": {
                    "lastName": "Smith
                }
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }


//$input = new ChangePasswordRequest([
//'PreviousPassword' => '/0zvOPBZhSRt:CC48wp%Jv"MMo47xM',
//'ProposedPassword' => 'p9jtshQDSgo_IOpk"wGuT?m%yG&4;R',
//'AccessToken' => 'F7358A38BFF27816416D7846FC1EE',
//]);
//
//    // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ChangePassword.html
//$expected = '
//                POST / HTTP/1.0
//                Content-Type: application/x-amz-json-1.1
//                X-AMZ-Target: AWSCognitoIdentityProviderService.ChangePassword
//
//                {
//                    "PreviousPassword": "/0zvOPBZhSRt:CC48wp%Jv\"MMo47xM",
//                    "ProposedPassword": "p9jtshQDSgo_IOpk\"wGuT?m%yG&4;R",
//                    "AccessToken": "F7358A38BFF27816416D7846FC1EE"
//                }
//                ';
//
//self::assertRequestEqualsHttpRequest($expected, $input->request());
//}
}
