<?php

namespace AsyncAws\StepFunctions\Result;

use AsyncAws\Core\Exception\UnparsableResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StopExecutionOutput extends Result
{
    /**
     * The date the execution is stopped.
     *
     * @var \DateTimeImmutable
     */
    private $stopDate;

    public function getStopDate(): \DateTimeImmutable
    {
        $this->initialize();

        return $this->stopDate;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->stopDate = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['stopDate'])) ?: throw new UnparsableResponse('Invalid timestamp received.');
    }
}
