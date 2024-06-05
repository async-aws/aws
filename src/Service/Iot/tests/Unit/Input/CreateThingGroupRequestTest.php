<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\CreateThingGroupRequest;
use AsyncAws\Iot\ValueObject\AttributePayload;
use AsyncAws\Iot\ValueObject\Tag;
use AsyncAws\Iot\ValueObject\ThingGroupProperties;

class CreateThingGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateThingGroupRequest([
            'thingGroupName' => 'unit1',
            'parentGroupName' => 'building1',
            'thingGroupProperties' => new ThingGroupProperties([
                'thingGroupDescription' => 'All things part of Unit 1',
                'attributePayload' => new AttributePayload([
                    'attributes' => ['id' => 'GHYEUZLZL'],
                    'merge' => false,
                ]),
            ]),
            'tags' => [new Tag([
                'Key' => 'exposition',
                'Value' => 'north',
            ])],
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_CreateThingGroup.html
        $expected = '
            POST /thing-groups/unit1 HTTP/1.1
            Content-type: application/json
            Accept: application/json

            {
                "parentGroupName": "building1",
                "tags": [
                   {
                      "Key": "exposition",
                      "Value": "north"
                   }
                ],
                "thingGroupProperties": {
                   "attributePayload": {
                      "attributes": {
                         "id" : "GHYEUZLZL"
                      },
                      "merge": false
                   },
                   "thingGroupDescription": "All things part of Unit 1"
                }
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
