<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\DeleteThingRequest;

class DeleteThingRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteThingRequest([
            'thingName' => 'hvac1',
            'expectedVersion' => 1337,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_DeleteThing.html
        $expected = '
            DELETE /things/hvac1?expectedVersion=1337 HTTP/1.0
            Content-Type: application/json
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
