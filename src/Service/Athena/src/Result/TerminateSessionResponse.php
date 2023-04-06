<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\SessionState;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class TerminateSessionResponse extends Result
{
    /**
     * The state of the session. A description of each state follows.
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

        $this->state = isset($data['State']) ? (string) $data['State'] : null;
    }
}
