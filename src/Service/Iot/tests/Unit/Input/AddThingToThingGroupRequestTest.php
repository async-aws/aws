<?php

namespace AsyncAws\Iot\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\AddThingToThingGroupRequest;

class AddThingToThingGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AddThingToThingGroupRequest([
            'thingGroupName' => 'hvac',
            'thingGroupArn' => 'hvac-arn',
            'thingName' => 'hvac-1',
            'thingArn' => 'hvac-1-arn',
            'overrideDynamicGroups' => false,
        ]);

        // see https://docs.aws.amazon.com/iot/latest/apireference/API_AddThingToThingGroup.html
        $expected = '
            PUT /thing-groups/addThingToThingGroup HTTP/1.1
            Content-type: application/json
            Accept: application/json

            {
            "overrideDynamicGroups": false,
            "thingArn": "hvac-1-arn",
            "thingGroupArn": "hvac-arn",
            "thingGroupName": "hvac",
            "thingName": "hvac-1"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
