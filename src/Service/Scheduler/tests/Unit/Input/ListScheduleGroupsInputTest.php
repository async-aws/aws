<?php

namespace AsyncAws\Scheduler\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Input\ListScheduleGroupsInput;

class ListScheduleGroupsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListScheduleGroupsInput([
            'MaxResults' => 20,
            'NamePrefix' => 'foo',
        ]);

        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_ListScheduleGroups.html
        $expected = '
            GET /schedule-groups?MaxResults=20&NamePrefix=foo HTTP/1.0
            Content-Type: application/json

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
