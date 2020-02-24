<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\InvocationRequest;

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
            'FunctionName' => 'MyFunction',
            'InvocationType' => 'Event',
            'LogType' => 'Tail',
            'ClientContext' => 'MyApp',
            'Payload' => 'fileb://file-path/input.json',
            'Qualifier' => '1',
        ]);

        // see example-1.json from SDK
        $expected = '{
            "ClientContext": "MyApp",
            "FunctionName": "MyFunction",
            "InvocationType": "Event",
            "LogType": "Tail",
            "Payload": "fileb:\\/\\/file-path\\/input.json",
            "Qualifier": "1"
        }';

        self::assertJsonStringEqualsJsonString($expected, $input->requestBody());
    }
}
