<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingTypesRequest;

class ListThingTypesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListThingTypesRequest([
            'nextToken' => 'change me',
            'maxResults' => 1337,
            'thingTypeName' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThingTypes.html
        $expected = '
            GET / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
