<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\ChangePasswordRequest;
use AsyncAws\Core\Test\TestCase;

class ChangePasswordRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ChangePasswordRequest([
            'PreviousPassword' => '/0zvOPBZhSRt:CC48wp%Jv"MMo47xM',
            'ProposedPassword' => 'p9jtshQDSgo_IOpk"wGuT?m%yG&4;R',
            'AccessToken' => 'F7358A38BFF27816416D7846FC1EE',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ChangePassword.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-AMZ-Target: AWSCognitoIdentityProviderService.ChangePassword

                {
                    "PreviousPassword": "/0zvOPBZhSRt:CC48wp%Jv\"MMo47xM",
                    "ProposedPassword": "p9jtshQDSgo_IOpk\"wGuT?m%yG&4;R",
                    "AccessToken": "F7358A38BFF27816416D7846FC1EE"
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
