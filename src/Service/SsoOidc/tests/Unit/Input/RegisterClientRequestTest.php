<?php

namespace AsyncAws\SsoOidc\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Input\RegisterClientRequest;

class RegisterClientRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new RegisterClientRequest([
            'clientName' => 'My IDE Plugin',
            'clientType' => 'public',
            'scopes' => ['sso:account:access', 'codewhisperer:completions'],
            'redirectUris' => ['127.0.0.1:PORT/oauth/callback'],
            'grantTypes' => ['authorization_code', 'refresh_token'],
            'issuerUrl' => 'https://identitycenter.amazonaws.com/ssoins-1111111111111111',
            'entitledApplicationArn' => 'arn:aws:sso::ACCOUNTID:application/ssoins-1111111111111111/apl-1111111111111111',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST /client/register HTTP/1.0
            Content-Type: application/json
            Accept: application/json

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
