<?php

namespace AsyncAws\SsoOidc\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Input\RegisterClientRequest;

class RegisterClientRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new RegisterClientRequest([
            'clientName' => 'change me',
            'clientType' => 'change me',
            'scopes' => ['change me'],
            'redirectUris' => ['change me'],
            'grantTypes' => ['change me'],
            'issuerUrl' => 'change me',
            'entitledApplicationArn' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/json

            {
            "clientName": "My IDE Plugin",
            "clientType": "public",
            "entitledApplicationArn": "arn:aws:sso::ACCOUNTID:application\\/ssoins-1111111111111111\\/apl-1111111111111111",
            "grantTypes": [
                "authorization_code",
                "refresh_token"
            ],
            "issuerUrl": "https:\\/\\/identitycenter.amazonaws.com\\/ssoins-1111111111111111",
            "redirectUris": [
                "127.0.0.1:PORT\\/oauth\\/callback"
            ],
            "scopes": [
                "sso:account:access",
                "codewhisperer:completions"
            ]
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
