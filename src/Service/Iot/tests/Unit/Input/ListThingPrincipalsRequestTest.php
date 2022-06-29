<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingPrincipalsRequest;

class ListThingPrincipalsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListThingPrincipalsRequest([
            'nextToken' => 'change me',
            'maxResults' => 1337,
            'thingName' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_ListThingPrincipals.html
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
