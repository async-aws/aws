<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\GetUserRequest;
use AsyncAws\Core\Test\TestCase;

class GetUserRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetUserRequest([
            'AccessToken' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_GetUser.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AWSCognitoIdentityProviderService.GetUser
            Accept: application/json

            {
                "AccessToken": "change me"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
