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
        }';

        self::assertJsonStringEqualsJsonString($expected, $this->input->requestBody());
    }

    public function testInvalidEnum(): void
    {
        $input = new ListLayerVersionsRequest([
            'LayerName' => 'demo',
            'CompatibleRuntime' => 'boom',
        ]);
        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage('Invalid parameter "CompatibleRuntime" when validating the "AsyncAws\Lambda\Input\ListLayerVersionsRequest". The value "boom" is not a valid "Runtime". Available values are dotnetcore1.0, dotnetcore2.0, dotnetcore2.1, go1.x, java11, java8, nodejs, nodejs10.x, nodejs12.x, nodejs4.3, nodejs4.3-edge, nodejs6.10, nodejs8.10, provided, python2.7, python3.6, python3.7, python3.8, ruby2.5.');
        $input->validate();
    }

    public function testRequestUrl(): void
    {
        $expected = '/2018-10-31/layers/demo/versions';

        self::assertSame($expected, $this->input->requestUri());
    }
}
