<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\ListResolversRequest;
use AsyncAws\Core\Test\TestCase;

class ListResolversRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListResolversRequest([
            'apiId' => 'api123',
            'typeName' => 'type',
            'nextToken' => 'token',
            'maxResults' => 1337,
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_ListResolvers.html
        $expected = '
            GET /v1/apis/api123/types/type/resolvers?maxResults=1337&nextToken=token HTTP/1.1
            Content-Type: application/json
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
