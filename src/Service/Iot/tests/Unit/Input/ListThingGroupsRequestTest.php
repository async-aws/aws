<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingGroupsRequest;

class ListThingGroupsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListThingGroupsRequest([
            'nextToken' => 'change me',
            'maxResults' => 1337,
            'parentGroup' => 'change me',
            'namePrefixFilter' => 'change me',
            'recursive' => false,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThingGroups.html
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
