<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingsRequest;

class ListThingsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListThingsRequest([
            'nextToken' => 'change me',
            'maxResults' => 1337,
            'attributeName' => 'change me',
            'attributeValue' => 'change me',
            'thingTypeName' => 'change me',
            'usePrefixAttributeValue' => false,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThings.html
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
