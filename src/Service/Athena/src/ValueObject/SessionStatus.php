<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\SessionState;

/**
 * Contains information about the status of a session.
 */
final class SessionStatus
{
    /**
     * The date and time that the session started.
     *
     * @var \DateTimeImmutable|null
     */
    private $startDateTime;

    /**
     * The most recent date and time that the session was modified.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModifiedDateTime;

    /**
     * The date and time that the session ended.
     *
     * @var \DateTimeImmutable|null
     */
    private $endDateTime;

    /**
     * The date and time starting at which the session became idle. Can be empty if the session is not currently idle.
     *
     * @var \DateTimeImmutable|null
     */
    private $idleSinceDateTime;

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
     * @var SessionState::*|string|null
     */
    private $state;

    /**
     * The reason for the session state change (for example, canceled because the session was terminated).
     *
     * @var string|null
     */
    private $stateChangeReason;

    /**
     * @param array{
     *   StartDateTime?: null|\DateTimeImmutable,
     *   LastModifiedDateTime?: null|\DateTimeImmutable,
     *   EndDateTime?: null|\DateTimeImmutable,
     *   IdleSinceDateTime?: null|\DateTimeImmutable,
     *   State?: null|SessionState::*|string,
     *   StateChangeReason?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->startDateTime = $input['StartDateTime'] ?? null;
        $this->lastModifiedDateTime = $input['LastModifiedDateTime'] ?? null;
        $this->endDateTime = $input['EndDateTime'] ?? null;
        $this->idleSinceDateTime = $input['IdleSinceDateTime'] ?? null;
        $this->state = $input['State'] ?? null;
        $this->stateChangeReason = $input['StateChangeReason'] ?? null;
    }

    /**
     * @param array{
     *   StartDateTime?: null|\DateTimeImmutable,
     *   LastModifiedDateTime?: null|\DateTimeImmutable,
     *   EndDateTime?: null|\DateTimeImmutable,
     *   IdleSinceDateTime?: null|\DateTimeImmutable,
     *   State?: null|SessionState::*|string,
     *   StateChangeReason?: null|string,
     * }|SessionStatus $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndDateTime(): ?\DateTimeImmutable
    {
        return $this->endDateTime;
    }

    public function getIdleSinceDateTime(): ?\DateTimeImmutable
    {
        return $this->idleSinceDateTime;
    }

    public function getLastModifiedDateTime(): ?\DateTimeImmutable
    {
        return $this->lastModifiedDateTime;
    }

    public function getStartDateTime(): ?\DateTimeImmutable
    {
        return $this->startDateTime;
    }

    /**
     * @return SessionState::*|string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    public function getStateChangeReason(): ?string
    {
        return $this->stateChangeReason;
    }
}
