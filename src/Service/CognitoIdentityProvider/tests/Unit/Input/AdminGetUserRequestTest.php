<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\AdminGetUserRequest;
use AsyncAws\Core\Test\TestCase;

class AdminGetUserRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AdminGetUserRequest([
            'UserPoolId' => 'us-east-1_1337oL33t',
            'Username' => '1c202820-8eb5-11ea-bc55-0242ac130003',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminGetUser.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-AMZ-Target: AWSCognitoIdentityProviderService.AdminGetUser

                {
                    "UserPoolId": "us-east-1_1337oL33t",
                    "Username": "1c202820-8eb5-11ea-bc55-0242ac130003"
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
