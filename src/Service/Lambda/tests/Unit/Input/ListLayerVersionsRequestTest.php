<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;

/**
 * @see https://docs.aws.amazon.com/lambda/latest/dg/API_ListLayerVersions.html
 */
class ListLayerVersionsRequestTest extends TestCase
{
    /**
     * @var ListLayerVersionsRequest
     */
    private $input;

    public function setUp(): void
    {
        $this->input = new ListLayerVersionsRequest([
            'CompatibleRuntime' => 'nodejs12.x',
            'LayerName' => 'demo',
        ]);

        parent::setUp();
    }

    public function testRequestBody(): void
    {
        $expected = '{
            "Action": "ListLayerVersions",
            "Version": "2015-03-31"
        }';

        self::assertJsonStringEqualsJsonString($expected, $this->input->requestBody());
    }

    public function testRequestUrl(): void
    {
        $expected = '/2018-10-31/layers/demo/versions';

        self::assertSame($expected, $this->input->requestUri());
    }
}
