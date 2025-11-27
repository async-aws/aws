<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\SessionState;
use AsyncAws\Athena\ValueObject\SessionStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetSessionStatusResponse extends Result
{
    /**
     * The session ID.
     *
     * @var string|null
     */
    private $sessionId;

    /**
     * Contains information about the status of the session.
     *
     * @var SessionStatus|null
     */
    private $status;

    public function getSessionId(): ?string
    {
        $this->initialize();

        return $this->sessionId;
    }

    public function getStatus(): ?SessionStatus
    {
        $this->initialize();

        return $this->status;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->sessionId = isset($data['SessionId']) ? (string) $data['SessionId'] : null;
        $this->status = empty($data['Status']) ? null : $this->populateResultSessionStatus($data['Status']);
    }

    private function populateResultSessionStatus(array $json): SessionStatus
    {
        return new SessionStatus([
            'StartDateTime' => (isset($json['StartDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['StartDateTime'])))) ? $d : null,
            'LastModifiedDateTime' => (isset($json['LastModifiedDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastModifiedDateTime'])))) ? $d : null,
            'EndDateTime' => (isset($json['EndDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['EndDateTime'])))) ? $d : null,
            'IdleSinceDateTime' => (isset($json['IdleSinceDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['IdleSinceDateTime'])))) ? $d : null,
            'State' => isset($json['State']) ? (!SessionState::exists((string) $json['State']) ? SessionState::UNKNOWN_TO_SDK : (string) $json['State']) : null,
            'StateChangeReason' => isset($json['StateChangeReason']) ? (string) $json['StateChangeReason'] : null,
        ]);
    }
}
