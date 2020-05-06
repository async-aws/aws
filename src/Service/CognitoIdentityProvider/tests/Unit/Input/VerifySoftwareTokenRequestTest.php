<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\VerifySoftwareTokenRequest;
use AsyncAws\Core\Test\TestCase;

class VerifySoftwareTokenRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new VerifySoftwareTokenRequest([
            'AccessToken' => 'B85A977AE91F811E8B1577CCA22C8',
            'Session' => 'SWxGBHyDUuLtv6s0WHKAU1N7kETzPehv',
            'UserCode' => '123456',
            'FriendlyDeviceName' => "Jon's phone",
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_VerifySoftwareToken.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-AMZ-Target: AWSCognitoIdentityProviderService.VerifySoftwareToken

                {
                    "AccessToken": "B85A977AE91F811E8B1577CCA22C8",
                    "Session": "SWxGBHyDUuLtv6s0WHKAU1N7kETzPehv",
                    "UserCode": "123456",
                    "FriendlyDeviceName": "Jon\'s phone"
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
