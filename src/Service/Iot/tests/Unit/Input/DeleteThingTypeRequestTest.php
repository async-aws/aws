<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\DeleteThingTypeRequest;

class DeleteThingTypeRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteThingTypeRequest([
            'thingTypeName' => 'hvac',
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_DeleteThingType.html
        $expected = '
            DELETE /thing-types/hvac HTTP/1.0
            Content-type: application/json
            Accept: application/json
            ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
