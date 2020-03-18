<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;

/**
 * @see https://docs.aws.amazon.com/lambda/latest/dg/API_ListLayerVersions.html
 */
class ListLayerVersionsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListLayerVersionsRequest([
            'CompatibleRuntime' => 'nodejs12.x',
            'LayerName' => 'demo',
        ]);

        $expected = '
            GET /2018-10-31/layers/demo/versions?CompatibleRuntime=nodejs12.x HTTP/1.0
            Content-Type: application/json
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testInvalidEnum(): void
    {
        $input = new ListLayerVersionsRequest([
            'LayerName' => 'demo',
            'CompatibleRuntime' => 'boom',
        ]);
        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage('Invalid parameter "CompatibleRuntime" for "AsyncAws\Lambda\Input\ListLayerVersionsRequest". The value "boom" is not a valid "Runtime".');
        $input->request();
    }
}
