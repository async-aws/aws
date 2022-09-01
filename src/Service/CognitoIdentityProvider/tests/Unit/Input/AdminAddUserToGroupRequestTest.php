<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\AdminAddUserToGroupRequest;
use AsyncAws\Core\Test\TestCase;

class AdminAddUserToGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AdminAddUserToGroupRequest([
            'UserPoolId' => 'us-east-test',
            'Username' => 'test_user',
            'GroupName' => 'test_group',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminAddUserToGroup.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-AMZ-Target: AWSCognitoIdentityProviderService.AdminAddUserToGroup

            {
                "UserPoolId": "us-east-test",
                "Username": "test_user",
                "GroupName": "test_group"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
