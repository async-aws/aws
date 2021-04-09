<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\QueryExecutionState;

/**
 * The completion date, current state, submission time, and state change reason (if applicable) for the query execution.
 */
final class QueryExecutionStatus
{
    /**
     * The state of query execution. `QUEUED` indicates that the query has been submitted to the service, and Athena will
     * execute the query as soon as resources are available. `RUNNING` indicates that the query is in execution phase.
     * `SUCCEEDED` indicates that the query completed without errors. `FAILED` indicates that the query experienced an error
     * and did not complete processing. `CANCELLED` indicates that a user input interrupted query execution.
     */
    private $state;

    /**
     * Further detail about the status of the query.
     */
    private $stateChangeReason;

    /**
     * The date and time that the query was submitted.
     */
    private $submissionDateTime;

    /**
     * The date and time that the query completed.
     */
    private $completionDateTime;

    /**
     * @param array{
     *   State?: null|QueryExecutionState::*,
     *   StateChangeReason?: null|string,
     *   SubmissionDateTime?: null|\DateTimeImmutable,
     *   CompletionDateTime?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->state = $input['State'] ?? null;
        $this->stateChangeReason = $input['StateChangeReason'] ?? null;
        $this->submissionDateTime = $input['SubmissionDateTime'] ?? null;
        $this->completionDateTime = $input['CompletionDateTime'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCompletionDateTime(): ?\DateTimeImmutable
    {
        return $this->completionDateTime;
    }

    /**
     * @return QueryExecutionState::*|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    public function getStateChangeReason(): ?string
    {
        return $this->stateChangeReason;
    }

    public function getSubmissionDateTime(): ?\DateTimeImmutable
    {
        return $this->submissionDateTime;
    }
}
