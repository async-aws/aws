<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\CalculationExecutionState;

/**
 * Contains information about the status of a notebook calculation.
 */
final class CalculationStatus
{
    /**
     * The date and time the calculation was submitted for processing.
     *
     * @var \DateTimeImmutable|null
     */
    private $submissionDateTime;

    /**
     * The date and time the calculation completed processing.
     *
     * @var \DateTimeImmutable|null
     */
    private $completionDateTime;

    /**
     * The state of the calculation execution. A description of each state follows.
     *
     * `CREATING` - The calculation is in the process of being created.
     *
     * `CREATED` - The calculation has been created and is ready to run.
     *
     * `QUEUED` - The calculation has been queued for processing.
     *
     * `RUNNING` - The calculation is running.
     *
     * `CANCELING` - A request to cancel the calculation has been received and the system is working to stop it.
     *
     * `CANCELED` - The calculation is no longer running as the result of a cancel request.
     *
     * `COMPLETED` - The calculation has completed without error.
     *
     * `FAILED` - The calculation failed and is no longer running.
     *
     * @var CalculationExecutionState::*|string|null
     */
    private $state;

    /**
     * The reason for the calculation state change (for example, the calculation was canceled because the session was
     * terminated).
     *
     * @var string|null
     */
    private $stateChangeReason;

    /**
     * @param array{
     *   SubmissionDateTime?: null|\DateTimeImmutable,
     *   CompletionDateTime?: null|\DateTimeImmutable,
     *   State?: null|CalculationExecutionState::*|string,
     *   StateChangeReason?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->submissionDateTime = $input['SubmissionDateTime'] ?? null;
        $this->completionDateTime = $input['CompletionDateTime'] ?? null;
        $this->state = $input['State'] ?? null;
        $this->stateChangeReason = $input['StateChangeReason'] ?? null;
    }

    /**
     * @param array{
     *   SubmissionDateTime?: null|\DateTimeImmutable,
     *   CompletionDateTime?: null|\DateTimeImmutable,
     *   State?: null|CalculationExecutionState::*|string,
     *   StateChangeReason?: null|string,
     * }|CalculationStatus $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCompletionDateTime(): ?\DateTimeImmutable
    {
        return $this->completionDateTime;
    }

    /**
     * @return CalculationExecutionState::*|string|null
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
