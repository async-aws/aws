<?php

namespace AsyncAws\Scheduler\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\Input\ListSchedulesInput;

class ListSchedulesInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListSchedulesInput([
            'GroupName' => 'foo',
            'MaxResults' => 20,
            'NamePrefix' => 'bar',
            'State' => ScheduleState::ENABLED,
        ]);

        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_ListSchedules.html
        $expected = '
            GET /schedules?MaxResults=20&ScheduleGroup=foo&NamePrefix=bar&State=ENABLED HTTP/1.0
            Content-Type: application/json

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
