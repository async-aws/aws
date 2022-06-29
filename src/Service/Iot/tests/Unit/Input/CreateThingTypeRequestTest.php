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
        self::fail('Not implemented');

        $input = new CreateThingTypeRequest([
            'thingTypeName' => 'change me',
            'thingTypeProperties' => new ThingTypeProperties([
                'thingTypeDescription' => 'change me',
                'searchableAttributes' => ['change me'],
            ]),
            'tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_CreateThingType.html
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
