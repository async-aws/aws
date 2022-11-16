<?php

namespace AsyncAws\Scheduler\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Input\GetScheduleInput;

class GetScheduleInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetScheduleInput([
            'GroupName' => 'foo',
            'Name' => 'bar',
        ]);

        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_GetSchedule.html
        $expected = '
            GET /schedules/bar?groupName=foo HTTP/1.0
            Content-Type: application/json

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
