<?php

namespace AsyncAws\Scheduler\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class UpdateScheduleOutput extends Result
{
    /**
     * The Amazon Resource Name (ARN) of the schedule that you updated.
     */
    private $scheduleArn;

    public function getScheduleArn(): string
    {
        $this->initialize();

        return $this->scheduleArn;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->scheduleArn = (string) $data['ScheduleArn'];
    }
}
