<?php

namespace AsyncAws\StepFunctions\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StartExecutionOutput extends Result
{
    /**
     * The Amazon Resource Name (ARN) that identifies the execution.
     */
    private $executionArn;

    /**
     * The date the execution is started.
     */
    private $startDate;

    public function getExecutionArn(): string
    {
        $this->initialize();

        return $this->executionArn;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        $this->initialize();

        return $this->startDate;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->executionArn = (string) $data['executionArn'];
        $this->startDate = /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['startDate']));
    }
}
