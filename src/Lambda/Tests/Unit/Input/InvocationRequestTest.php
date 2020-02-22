<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Lambda\Input\InvocationRequest;
use PHPUnit\Framework\TestCase;

class InvocationRequestTest extends TestCase
{
    public function testFunctionName()
    {
        $input = InvocationRequest::create(['FunctionName' => 'foobar']);
        $uri = $input->requestUri();

        self::assertStringContainsString('foobar', $uri);
    }

    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new InvocationRequest([
            'FunctionName' => 'change me',
            'InvocationType' => 'change me',
            'LogType' => 'change me',
            'ClientContext' => 'change me',
            'Payload' => 'change me',
            'Qualifier' => 'change me',
        ]);

        $expected = '{"change": "it"}';

        self::assertJsonStringEqualsJsonString($expected, $input->requestBody());
    }
}
