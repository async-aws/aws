<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\ListApiKeysRequest;
use AsyncAws\Core\Test\TestCase;

class ListApiKeysRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListApiKeysRequest([
            'apiId' => 'api123',
            'nextToken' => 'token',
            'maxResults' => 1337,
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_ListApiKeys.html
        $expected = '
            GET /v1/apis/api123/apikeys?maxResults=1337&nextToken=token HTTP/1.1
            Content-Type: application/json
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
