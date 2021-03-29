<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\DeleteFunctionRequest;

class DeleteFunctionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new DeleteFunctionRequest([
            'FunctionName' => 'change me',
            'Qualifier' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
            DELETE / HTTP/1.0
            Content-Type: application/json

            {
            "FunctionName": "my-function",
            "Qualifier": "1"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
