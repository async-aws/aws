<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\AdminResetUserPasswordRequest;
use AsyncAws\Core\Test\TestCase;

class AdminResetUserPasswordRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AdminResetUserPasswordRequest([
            'UserPoolId' => 'us-east-1_1337oL33t',
            'Username' => '1c202820-8eb5-11ea-bc55-0242ac130003',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminResetUserPassword.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-AMZ-Target: AWSCognitoIdentityProviderService.AdminResetUserPassword
            Accept: application/json

            {
                "UserPoolId": "us-east-1_1337oL33t",
                "Username": "1c202820-8eb5-11ea-bc55-0242ac130003"
            }
            ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
