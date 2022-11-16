<?php

namespace AsyncAws\Scheduler\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Input\GetScheduleGroupInput;

class GetScheduleGroupInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetScheduleGroupInput([
            'Name' => 'foo',
        ]);

        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_GetScheduleGroup.html
        $expected = '
            GET /schedule-groups/foo HTTP/1.0
            Content-Type: application/json

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
