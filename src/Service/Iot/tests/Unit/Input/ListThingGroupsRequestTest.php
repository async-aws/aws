<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingGroupsRequest;

class ListThingGroupsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListThingGroupsRequest([
            'nextToken' => 'n3x7',
            'maxResults' => 888,
            'parentGroup' => 'building1',
            'namePrefixFilter' => 'unit',
            'recursive' => false,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingGroups.html
        $expected = '
            GET /thing-groups?maxResults=888&namePrefixFilter=unit&nextToken=n3x7&parentGroup=building1&recursive=false HTTP/1.1
            Content-type: application/json
            Accept: application/json
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
