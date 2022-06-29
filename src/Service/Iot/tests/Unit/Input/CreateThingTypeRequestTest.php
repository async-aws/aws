<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\CreateThingTypeRequest;
use AsyncAws\Iot\ValueObject\Tag;
use AsyncAws\Iot\ValueObject\ThingTypeProperties;

class CreateThingTypeRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateThingTypeRequest([
            'thingTypeName' => 'hvac',
            'thingTypeProperties' => new ThingTypeProperties([
                'thingTypeDescription' => 'HVAC',
                'searchableAttributes' => ['air-conditionner'],
            ]),
            'tags' => [new Tag([
                'Key' => 'default_temperature',
                'Value' => '18',
            ])],
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThingType.html
        $expected = '
            POST /thing-types/hvac HTTP/1.1
            Content-type: application/json

            {
                "tags": [
                    {
                        "Key": "default_temperature",
                        "Value": "18"
                    }
                ],
                "thingTypeProperties": {
                    "searchableAttributes": [ "air-conditionner" ],
                    "thingTypeDescription": "HVAC"
                }
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
