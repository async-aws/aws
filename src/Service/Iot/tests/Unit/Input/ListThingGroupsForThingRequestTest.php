<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingGroupsForThingRequest;

class ListThingGroupsForThingRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListThingGroupsForThingRequest([
            'thingName' => 'hvac1',
            'nextToken' => '0th3r',
            'maxResults' => 42,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingGroupsForThing.html
        $expected = '
            GET /things/hvac1/thing-groups?maxResults=42&nextToken=0th3r HTTP/1.0
            Content-type: application/json
            Accept: application/json
            ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
