<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\GetAliasRequest;

class GetAliasRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetAliasRequest([
            'FunctionName' => 'my-function',
            'Name' => 'BLUE',
        ]);

        // see https://docs.aws.amazon.com/lambda/latest/APIReference/API_GetAlias.html
        $expected = '
            GET /2015-03-31/functions/my-function/aliases/BLUE HTTP/1.0
            Content-type: application/json
            Accept: application/json
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
