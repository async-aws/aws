<?php

namespace AsyncAws\Scheduler\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateScheduleGroupOutput extends Result
{
    /**
     * The Amazon Resource Name (ARN) of the schedule group.
     */
    private $scheduleGroupArn;

    public function getScheduleGroupArn(): string
    {
        $this->initialize();

        return $this->scheduleGroupArn;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->scheduleGroupArn = (string) $data['ScheduleGroupArn'];
    }
}
