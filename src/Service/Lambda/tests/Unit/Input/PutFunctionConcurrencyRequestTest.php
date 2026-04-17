<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\PutFunctionConcurrencyRequest;

class PutFunctionConcurrencyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutFunctionConcurrencyRequest([
            'FunctionName' => 'my-function',
            'ReservedConcurrentExecutions' => 100,
        ]);

        // see https://docs.aws.amazon.com/lambda/latest/APIReference/API_PutFunctionConcurrency.html
        $expected = '
            PUT /2017-10-31/functions/my-function/concurrency HTTP/1.1
            Content-type: application/json
            Accept: application/json

            {"ReservedConcurrentExecutions":100}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
