<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingsInThingGroupRequest;

class ListThingsInThingGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListThingsInThingGroupRequest([
            'thingGroupName' => 'unit1',
            'recursive' => false,
            'nextToken' => 't0k3n',
            'maxResults' => 20,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingsInThingGroup.html
        $expected = '
            GET /thing-groups/unit1/things?maxResults=20&nextToken=t0k3n&recursive=false HTTP/1.1
            Content-Type: application/json
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
