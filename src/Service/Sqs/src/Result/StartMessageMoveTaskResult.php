<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StartMessageMoveTaskResult extends Result
{
    /**
     * An identifier associated with a message movement task. You can use this identifier to cancel a specified message
     * movement task using the `CancelMessageMoveTask` action.
     *
     * @var string|null
     */
    private $taskHandle;

    public function getTaskHandle(): ?string
    {
        $this->initialize();

        return $this->taskHandle;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->taskHandle = isset($data['TaskHandle']) ? (string) $data['TaskHandle'] : null;
    }
}
