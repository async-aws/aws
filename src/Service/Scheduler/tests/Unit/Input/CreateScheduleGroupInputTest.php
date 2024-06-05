<?php

namespace AsyncAws\Scheduler\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Input\CreateScheduleGroupInput;
use AsyncAws\Scheduler\ValueObject\Tag;

class CreateScheduleGroupInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateScheduleGroupInput([
            'ClientToken' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'Name' => 'foo',
            'Tags' => [
                new Tag([
                    'Key' => 'bar',
                    'Value' => 'baz',
                ]),
            ],
        ]);

        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_CreateScheduleGroup.html
        $expected = '
            POST /schedule-groups/foo HTTP/1.0
            Content-type: application/json
            Accept: application/json

            {
                "ClientToken": "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "Tags": [
                    {
                        "Key": "bar",
                        "Value": "baz"
                    }
                ]
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
