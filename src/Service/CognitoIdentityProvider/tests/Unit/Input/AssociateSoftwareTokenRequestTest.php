<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\AssociateSoftwareTokenRequest;
use AsyncAws\Core\Test\TestCase;

class AssociateSoftwareTokenRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AssociateSoftwareTokenRequest([
            'AccessToken' => '8D4TH59OFqfhIlRTlVZr4CCiJof7YYOF',
            'Session' => 'LpimcJ4whzL2BWVGu8B6',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AssociateSoftwareToken.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-AMZ-Target: AWSCognitoIdentityProviderService.AssociateSoftwareToken

                {
                    "AccessToken": "8D4TH59OFqfhIlRTlVZr4CCiJof7YYOF",
                    "Session": "LpimcJ4whzL2BWVGu8B6"
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
