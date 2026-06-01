<?php

namespace AsyncAws\Sso\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\ListAccountsRequest;

class ListAccountsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListAccountsRequest([
            'nextToken' => 'eyJuZXh0VG9rZW4iOm51bGx9',
            'maxResults' => 25,
            'accessToken' => 'eyJlbmMiOiJBMjU2R0NNIn0EXAMPLEACCESSTOKEN',
        ]);

        // see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_ListAccounts.html
        $expected = '
            GET /assignment/accounts?next_token=eyJuZXh0VG9rZW4iOm51bGx9&max_result=25 HTTP/1.0
            Content-Type: application/json
            Accept: application/json
            x-amz-sso_bearer_token: eyJlbmMiOiJBMjU2R0NNIn0EXAMPLEACCESSTOKEN
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
