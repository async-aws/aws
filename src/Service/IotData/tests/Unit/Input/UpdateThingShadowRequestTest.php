<?php

namespace AsyncAws\IotData\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\IotData\Input\UpdateThingShadowRequest;

class UpdateThingShadowRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UpdateThingShadowRequest([
            'thingName' => 'unit21_hvac',
            'shadowName' => 'temperature',
            'payload' => json_encode(['state' => ['reported' => ['temperature' => 21]]]),
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_Operations_AWS_IoT_Data_Plane.html/API_UpdateThingShadow.html
        $expected = '
            POST /things/unit21_hvac/shadow?name=temperature HTTP/1.0
            Content-Type: application/json

            {
                "state": {
                    "reported": {
                        "temperature": 21
                    }
                }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
