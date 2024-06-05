<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingsRequest;

class ListThingsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListThingsRequest([
            'nextToken' => '4ft3r',
            'maxResults' => 15,
            'attributeName' => 'id',
            'attributeValue' => 'mYiD',
            'thingTypeName' => 'hvac',
            'usePrefixAttributeValue' => false,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThings.html
        $expected = '
            GET /things?attributeName=id&attributeValue=mYiD&maxResults=15&nextToken=4ft3r&thingTypeName=hvac&usePrefixAttributeValue=false HTTP/1.1
            Content-type: application/json
            Accept: application/json
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
