<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListVersionsByFunctionRequest;

class ListVersionsByFunctionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListVersionsByFunctionRequest([
            'FunctionName' => 'change me',
            'Marker' => 'change me',
            'MaxItems' => 1337,
        ]);

        // see example-1.json from SDK
        $expected = '
            GET / HTTP/1.0
            Content-Type: application/json

            {
            "FunctionName": "my-function"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
