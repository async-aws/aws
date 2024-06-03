<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\AdminRemoveUserFromGroupRequest;
use AsyncAws\Core\Test\TestCase;

class AdminRemoveUserFromGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AdminRemoveUserFromGroupRequest([
            'UserPoolId' => 'us-east-test',
            'Username' => 'test_user',
            'GroupName' => 'test_group',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminRemoveUserFromGroup.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-AMZ-Target: AWSCognitoIdentityProviderService.AdminRemoveUserFromGroup
            Accept: application/json

            {
                "UserPoolId": "us-east-test",
                "Username": "test_user",
                "GroupName": "test_group"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
