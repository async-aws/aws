<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\AdminSetUserPasswordRequest;
use AsyncAws\Core\Test\TestCase;

class AdminSetUserPasswordRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AdminSetUserPasswordRequest([
            'UserPoolId' => 'mypoolid_1234',
            'Username' => 'roger@that.com',
            'Password' => 'P4ssw0rd',
            'Permanent' => false,
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminSetUserPassword.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AWSCognitoIdentityProviderService.AdminSetUserPassword

            {
                "UserPoolId": "mypoolid_1234",
                "Username": "roger@that.com",
                "Password": "P4ssw0rd",
                "Permanent": false
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
