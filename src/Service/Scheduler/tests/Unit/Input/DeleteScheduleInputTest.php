<?php

namespace AsyncAws\Scheduler\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Input\DeleteScheduleInput;

class DeleteScheduleInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteScheduleInput([
            'ClientToken' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'GroupName' => 'foo',
            'Name' => 'bar',
        ]);

        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_DeleteSchedule.html
        $expected = '
            DELETE /schedules/bar?clientToken=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa&groupName=foo HTTP/1.0
            Content-Type: application/json

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
