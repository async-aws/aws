<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListVersionsByFunctionRequest;

class ListVersionsByFunctionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListVersionsByFunctionRequest([
            'FunctionName' => 'MyFunction',
            'Marker' => 'xxyy',
            'MaxItems' => 1337,
        ]);

        // see example-1.json from SDK
        $expected = '
            GET /2015-03-31/functions/MyFunction/versions?Marker=xxyy&MaxItems=1337 HTTP/1.0
            Content-Type: application/json

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
