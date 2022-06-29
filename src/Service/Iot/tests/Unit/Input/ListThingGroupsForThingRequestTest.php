<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingGroupsForThingRequest;

class ListThingGroupsForThingRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListThingGroupsForThingRequest([
            'thingName' => 'change me',
            'nextToken' => 'change me',
            'maxResults' => 1337,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThingGroupsForThing.html
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
