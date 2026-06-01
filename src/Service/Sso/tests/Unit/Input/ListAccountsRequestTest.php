<?php

namespace AsyncAws\Sso\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\ListAccountsRequest;

class ListAccountsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListAccountsRequest([
            'nextToken' => 'change me',
            'maxResults' => 1337,
            'accessToken' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_ListAccounts.html
        $expected = '
            GET / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
