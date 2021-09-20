<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\ListApiKeysRequest;
use AsyncAws\Core\Test\TestCase;

class ListApiKeysRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListApiKeysRequest([
            'apiId' => 'change me',
            'nextToken' => 'change me',
            'maxResults' => 1337,
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_ListApiKeys.html
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
