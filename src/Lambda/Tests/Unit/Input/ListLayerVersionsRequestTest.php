<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use PHPUnit\Framework\TestCase;

class ListLayerVersionsRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new ListLayerVersionsRequest([
            'CompatibleRuntime' => 'change me',
            'LayerName' => 'change me',
            'Marker' => 'change me',
            'MaxItems' => 1337,
        ]);

        $expected = '{"change": "it"}';

        self::assertJsonStringEqualsJsonString($expected, $input->requestBody());
    }
}
