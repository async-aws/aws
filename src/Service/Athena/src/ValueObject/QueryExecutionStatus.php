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
     *
     * > Athena automatically retries your queries in cases of certain transient errors. As a result, you may see the query
     * > state transition from `RUNNING` or `FAILED` to `QUEUED`.
     *
     * @var QueryExecutionState::*|string|null
     */
    private $state;

    /**
     * Further detail about the status of the query.
     *
     * @var string|null
     */
    private $stateChangeReason;

    /**
     * The date and time that the query was submitted.
     *
     * @var \DateTimeImmutable|null
     */
    private $submissionDateTime;

    /**
     * The date and time that the query completed.
     *
     * @var \DateTimeImmutable|null
     */
    private $completionDateTime;

    /**
     * Provides information about an Athena query error.
     *
     * @var AthenaError|null
     */
    private $athenaError;

    /**
     * @param array{
     *   State?: null|QueryExecutionState::*|string,
     *   StateChangeReason?: null|string,
     *   SubmissionDateTime?: null|\DateTimeImmutable,
     *   CompletionDateTime?: null|\DateTimeImmutable,
     *   AthenaError?: null|AthenaError|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->state = $input['State'] ?? null;
        $this->stateChangeReason = $input['StateChangeReason'] ?? null;
        $this->submissionDateTime = $input['SubmissionDateTime'] ?? null;
        $this->completionDateTime = $input['CompletionDateTime'] ?? null;
        $this->athenaError = isset($input['AthenaError']) ? AthenaError::create($input['AthenaError']) : null;
    }

    /**
     * @param array{
     *   State?: null|QueryExecutionState::*|string,
     *   StateChangeReason?: null|string,
     *   SubmissionDateTime?: null|\DateTimeImmutable,
     *   CompletionDateTime?: null|\DateTimeImmutable,
     *   AthenaError?: null|AthenaError|array,
     * }|QueryExecutionStatus $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAthenaError(): ?AthenaError
    {
        return $this->athenaError;
    }

    public function getCompletionDateTime(): ?\DateTimeImmutable
    {
        return $this->completionDateTime;
    }

    /**
     * @return QueryExecutionState::*|string|null
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
