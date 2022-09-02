<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\RevokeTokenRequest;
use AsyncAws\Core\Test\TestCase;

class RevokeTokenRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new RevokeTokenRequest([
            'Token' => 'refresh_token',
            'ClientId' => 'client_id',
            'ClientSecret' => 'client_secret',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_RevokeToken.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-AMZ-Target: AWSCognitoIdentityProviderService.RevokeToken

            {
              "Token": "refresh_token",
              "ClientId": "client_id",
              "ClientSecret": "client_secret"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
