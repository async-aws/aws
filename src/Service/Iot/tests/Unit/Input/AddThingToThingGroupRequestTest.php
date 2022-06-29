<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\AddThingToThingGroupRequest;

class AddThingToThingGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new AddThingToThingGroupRequest([
            'thingGroupName' => 'change me',
            'thingGroupArn' => 'change me',
            'thingName' => 'change me',
            'thingArn' => 'change me',
            'overrideDynamicGroups' => false,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_AddThingToThingGroup.html
        $expected = '
            PUT / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
