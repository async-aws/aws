<?php

namespace AsyncAws\Sso\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\ListAccountRolesRequest;

class ListAccountRolesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListAccountRolesRequest([
            'nextToken' => 'eyJuZXh0VG9rZW4iOm51bGx9',
            'maxResults' => 50,
            'accessToken' => 'eyJlbmMiOiJBMjU2R0NNIn0EXAMPLEACCESSTOKEN',
            'accountId' => '123456789011',
        ]);

        // see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_ListAccountRoles.html
        $expected = '
            GET /assignment/roles?next_token=eyJuZXh0VG9rZW4iOm51bGx9&max_result=50&account_id=123456789011 HTTP/1.0
            Content-Type: application/json
            Accept: application/json
            x-amz-sso_bearer_token: eyJlbmMiOiJBMjU2R0NNIn0EXAMPLEACCESSTOKEN
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
