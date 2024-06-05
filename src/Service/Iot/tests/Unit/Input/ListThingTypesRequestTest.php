<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingTypesRequest;

class ListThingTypesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListThingTypesRequest([
            'nextToken' => 'n3x7',
            'maxResults' => 10,
            'thingTypeName' => 'hvac',
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingTypes.html
        $expected = '
            GET /thing-types?maxResults=10&nextToken=n3x7&thingTypeName=hvac HTTP/1.1
            Content-type: application/json
            Accept: application/json
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
