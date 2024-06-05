<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\ListUsersRequest;
use AsyncAws\Core\Test\TestCase;

class ListUsersRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListUsersRequest([
            'UserPoolId' => 'us-east-1_1337oL33t',
            'AttributesToGet' => ['phone_number', 'given_name'],
            'Limit' => 1337,
            'PaginationToken' => 'D76B595F42428CB8',
            'Filter' => 'given_name = "Jon"',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ListUsers.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-AMZ-Target: AWSCognitoIdentityProviderService.ListUsers
                Accept: application/json


                {
                    "UserPoolId": "us-east-1_1337oL33t",
                    "AttributesToGet": ["phone_number","given_name"],
                    "Limit": 1337,
                    "PaginationToken": "D76B595F42428CB8",
                    "Filter": "given_name = \\"Jon\\""
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
