<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListAliasesRequest;

class ListAliasesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListAliasesRequest([
            'FunctionName' => 'my-function',
            'FunctionVersion' => '1',
            'Marker' => 'xxyy',
            'MaxItems' => 1337,
        ]);

        // see https://docs.aws.amazon.com/lambda/latest/APIReference/API_ListAliases.html
        $expected = '
            GET /2015-03-31/functions/my-function/aliases?FunctionVersion=1&Marker=xxyy&MaxItems=1337 HTTP/1.0
            Content-type: application/json
            Accept: application/json
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
