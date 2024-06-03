<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\DeleteThingGroupRequest;

class DeleteThingGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteThingGroupRequest([
            'thingGroupName' => 'unit1',
            'expectedVersion' => 1337,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_DeleteThingGroup.html
        $expected = '
            DELETE /thing-groups/unit1?expectedVersion=1337 HTTP/1.0
            Content-type: application/json
            Accept: application/json
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
