<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\SessionState;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StartSessionResponse extends Result
{
    /**
     * The session ID.
     */
    private $sessionId;

    /**
     * The state of the session. A description of each state follows.
     */
    private $state;

    public function getSessionId(): ?string
    {
        $this->initialize();

        return $this->sessionId;
    }

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

        $this->sessionId = isset($data['SessionId']) ? (string) $data['SessionId'] : null;
        $this->state = isset($data['State']) ? (string) $data['State'] : null;
    }
}
