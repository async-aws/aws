<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\CalculationExecutionState;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StartCalculationExecutionResponse extends Result
{
    /**
     * The calculation execution UUID.
     */
    private $calculationExecutionId;

    /**
     * `CREATING` - The calculation is in the process of being created.
     */
    private $state;

    public function getCalculationExecutionId(): ?string
    {
        $this->initialize();

        return $this->calculationExecutionId;
    }

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

        $this->calculationExecutionId = isset($data['CalculationExecutionId']) ? (string) $data['CalculationExecutionId'] : null;
        $this->state = isset($data['State']) ? (string) $data['State'] : null;
    }
}
