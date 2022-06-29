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
        self::fail('Not implemented');

        $input = new CreateThingGroupRequest([
            'thingGroupName' => 'change me',
            'parentGroupName' => 'change me',
            'thingGroupProperties' => new ThingGroupProperties([
                'thingGroupDescription' => 'change me',
                'attributePayload' => new AttributePayload([
                    'attributes' => ['change me' => 'change me'],
                    'merge' => false,
                ]),
            ]),
            'tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_CreateThingGroup.html
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
