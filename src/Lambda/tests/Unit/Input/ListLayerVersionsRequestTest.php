<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;

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

        // see https://docs.aws.amazon.com/Lambda/latest/APIReference/API_ListLayerVersions.html
        $expected = '{
            "change": "it"
        }';

        self::assertJsonStringEqualsJsonString($expected, $input->requestBody());
    }
}
