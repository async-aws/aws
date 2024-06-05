<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\DeleteFunctionRequest;

class DeleteFunctionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteFunctionRequest([
            'FunctionName' => 'my-function',
            'Qualifier' => '4711',
        ]);

        // see example-1.json from SDK
        $expected = '
            DELETE /2015-03-31/functions/my-function?Qualifier=4711  HTTP/1.0
            Content-type: application/json
            Accept: application/json
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
