<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\AdminConfirmSignUpRequest;
use AsyncAws\Core\Test\TestCase;

class AdminConfirmSignUpRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AdminConfirmSignUpRequest([
            'UserPoolId' => 'us-east-1_1337oL33t',
            'Username' => 'albert@mousquetaire.com',
            'ClientMetadata' => ['service' => 'supplychain'],
        ]);

        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_AdminConfirmSignUp.html
        $expected = '
        POST / HTTP/1.0
        Content-Type: application/x-amz-json-1.1
        X-AMZ-Target: AWSCognitoIdentityProviderService.AdminConfirmSignUp

        {
            "UserPoolId": "us-east-1_1337oL33t",
            "Username": "albert@mousquetaire.com",
            "ClientMetadata": {
                "service": "supplychain"
            }
        }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
