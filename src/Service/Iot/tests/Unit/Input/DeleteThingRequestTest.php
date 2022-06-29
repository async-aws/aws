<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\DeleteThingRequest;

class DeleteThingRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new DeleteThingRequest([
            'thingName' => 'change me',
            'expectedVersion' => 1337,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_DeleteThing.html
        $expected = '
            DELETE / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
