<?php

namespace AsyncAws\Scheduler\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Input\DeleteScheduleGroupInput;

class DeleteScheduleGroupInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteScheduleGroupInput([
            'ClientToken' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'Name' => 'foo',
        ]);

        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_DeleteScheduleGroup.html
        $expected = '
            DELETE /schedule-groups/foo?clientToken=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa HTTP/1.0
            Content-Type: application/json

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
