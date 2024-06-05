<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListFunctionsRequest;

class ListFunctionsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListFunctionsRequest([
            'MasterRegion' => 'eu-central-1',
            'FunctionVersion' => 'ALL',
            'Marker' => 'xxyy',
            'MaxItems' => 1337,
        ]);

        // see https://docs.aws.amazon.com/lambda/latest/APIReference/API_ListFunctions.html
        $expected = '
            GET /2015-03-31/functions/?FunctionVersion=ALL&Marker=xxyy&MasterRegion=eu-central-1&MaxItems=1337 HTTP/1.1
            Content-type: application/json
            Accept: application/json
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
