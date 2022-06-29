<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingsInThingGroupRequest;

class ListThingsInThingGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListThingsInThingGroupRequest([
            'thingGroupName' => 'change me',
            'recursive' => false,
            'nextToken' => 'change me',
            'maxResults' => 1337,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThingsInThingGroup.html
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
