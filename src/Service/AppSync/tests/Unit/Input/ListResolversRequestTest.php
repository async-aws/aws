<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\ListResolversRequest;
use AsyncAws\Core\Test\TestCase;

class ListResolversRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListResolversRequest([
            'apiId' => 'change me',
            'typeName' => 'change me',
            'nextToken' => 'change me',
            'maxResults' => 1337,
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_ListResolvers.html
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
