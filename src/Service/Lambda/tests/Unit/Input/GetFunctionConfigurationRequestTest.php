<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\GetFunctionConfigurationRequest;

class GetFunctionConfigurationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetFunctionConfigurationRequest([
            'FunctionName' => 'test-func',
            'Qualifier' => 'change',
        ]);

        // see https://docs.aws.amazon.com/lambda/latest/APIReference/API_GetFunctionConfiguration.html
        $expected = '
            GET /2015-03-31/functions/test-func/configuration?Qualifier=change HTTP/1.0
            Content-Type: application/json
        ';
        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
