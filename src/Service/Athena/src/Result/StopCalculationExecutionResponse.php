<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\CalculationExecutionState;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StopCalculationExecutionResponse extends Result
{
    /**
     * `CREATING` - The calculation is in the process of being created.
     *
     * `CREATED` - The calculation has been created and is ready to run.
     *
     * `QUEUED` - The calculation has been queued for processing.
     *
     * `RUNNING` - The calculation is running.
     *
     * `CANCELING` - A request to cancel the calculation has been received and the system is working to stop it.
     *
     * `CANCELED` - The calculation is no longer running as the result of a cancel request.
     *
     * `COMPLETED` - The calculation has completed without error.
     *
     * `FAILED` - The calculation failed and is no longer running.
     *
     * @var CalculationExecutionState::*|null
     */
    private $state;

    /**
     * @return CalculationExecutionState::*|null
     */
    public function getState(): ?string
    {
        $this->initialize();

        return $this->state;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->state = isset($data['State']) ? (!CalculationExecutionState::exists((string) $data['State']) ? CalculationExecutionState::UNKNOWN_TO_SDK : (string) $data['State']) : null;
    }
}
