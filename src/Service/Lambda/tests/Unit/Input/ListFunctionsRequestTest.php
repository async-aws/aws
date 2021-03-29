<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListFunctionsRequest;

class ListFunctionsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListFunctionsRequest([
            'MasterRegion' => 'change me',
            'FunctionVersion' => 'change me',
            'Marker' => 'change me',
            'MaxItems' => 1337,
        ]);

        // see https://docs.aws.amazon.com/lambda/latest/APIReference/API_ListFunctions.html
        $expected = '
            GET / HTTP/1.0
            Content-Type: application/json

            []
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
