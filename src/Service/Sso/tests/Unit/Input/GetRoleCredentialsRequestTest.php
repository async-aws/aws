<?php

namespace AsyncAws\Sso\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\GetRoleCredentialsRequest;

class GetRoleCredentialsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetRoleCredentialsRequest([
            'roleName' => 'RoleName',
            'accountId' => 'AccountId',
            'accessToken' => 'AccessToken',
        ]);

        // see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_GetRoleCredentials.html
        $expected = '
            GET /federation/credentials?role_name=RoleName&account_id=AccountId HTTP/1.0
            Content-type: application/json
            Accept: application/json
            x-amz-sso_bearer_token: AccessToken
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
