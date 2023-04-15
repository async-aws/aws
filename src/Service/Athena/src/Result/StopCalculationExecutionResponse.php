<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\CalculationExecutionState;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StopCalculationExecutionResponse extends Result
{
    /**
     * `CREATING` - The calculation is in the process of being created.
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

        $this->state = isset($data['State']) ? (string) $data['State'] : null;
    }
}
