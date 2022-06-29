<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\CreateThingRequest;
use AsyncAws\Iot\ValueObject\AttributePayload;

class CreateThingRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new CreateThingRequest([
            'thingName' => 'change me',
            'thingTypeName' => 'change me',
            'attributePayload' => new AttributePayload([
                'attributes' => ['change me' => 'change me'],
                'merge' => false,
            ]),
            'billingGroupName' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_CreateThing.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
