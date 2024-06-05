<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\CreateThingRequest;
use AsyncAws\Iot\ValueObject\AttributePayload;

class CreateThingRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateThingRequest([
            'thingName' => 'hvac-1',
            'thingTypeName' => 'hvac',
            'attributePayload' => new AttributePayload([
                'attributes' => ['knx_ip' => '90.234.10.1'],
                'merge' => false,
            ]),
            'billingGroupName' => 'air-conditioning',
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThing.html
        $expected = '
            POST /things/hvac-1 HTTP/1.0
            Content-type: application/json
            Accept: application/json

            {
                "attributePayload": {
                   "attributes": {
                      "knx_ip" : "90.234.10.1"
                   },
                   "merge": false
                },
                "billingGroupName": "air-conditioning",
                "thingTypeName": "hvac"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
