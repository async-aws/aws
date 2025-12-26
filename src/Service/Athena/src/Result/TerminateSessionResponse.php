<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\SessionState;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class TerminateSessionResponse extends Result
{
    /**
     * The state of the session. A description of each state follows.
     *
     * `CREATING` - The session is being started, including acquiring resources.
     *
     * `CREATED` - The session has been started.
     *
     * `IDLE` - The session is able to accept a calculation.
     *
     * `BUSY` - The session is processing another task and is unable to accept a calculation.
     *
     * `TERMINATING` - The session is in the process of shutting down.
     *
     * `TERMINATED` - The session and its resources are no longer running.
     *
     * `DEGRADED` - The session has no healthy coordinators.
     *
     * `FAILED` - Due to a failure, the session and its resources are no longer running.
     *
     * @var SessionState::*|null
     */
    private $state;

    /**
     * @return SessionState::*|null
     */
    public function getState(): ?string
    {
        $this->initialize();

        return $this->state;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->state = isset($data['State']) ? (!SessionState::exists((string) $data['State']) ? SessionState::UNKNOWN_TO_SDK : (string) $data['State']) : null;
    }
}
