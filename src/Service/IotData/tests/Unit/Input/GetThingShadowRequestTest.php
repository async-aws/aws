<?php

namespace AsyncAws\IotData\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\IotData\Input\GetThingShadowRequest;

class GetThingShadowRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetThingShadowRequest([
            'thingName' => 'unit21_hvac',
            'shadowName' => 'temperature',
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_Operations_AWS_IoT_Data_Plane.html/API_GetThingShadow.html
        $expected = '
            GET /things/unit21_hvac/shadow?name=temperature HTTP/1.0
            Content-type: application/json
            Accept: application/json
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
