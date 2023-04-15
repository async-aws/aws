<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\CalculationExecutionState;

/**
 * Contains information about the status of the calculation.
 */
final class CalculationStatus
{
    /**
     * The date and time the calculation was submitted for processing.
     */
    private $submissionDateTime;

    /**
     * The date and time the calculation completed processing.
     */
    private $completionDateTime;

    /**
     * The state of the calculation execution. A description of each state follows.
     */
    private $state;

    /**
     * The reason for the calculation state change (for example, the calculation was canceled because the session was
     * terminated).
     */
    private $stateChangeReason;

    /**
     * @param array{
     *   SubmissionDateTime?: null|\DateTimeImmutable,
     *   CompletionDateTime?: null|\DateTimeImmutable,
     *   State?: null|CalculationExecutionState::*,
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

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCompletionDateTime(): ?\DateTimeImmutable
    {
        return $this->completionDateTime;
    }

    /**
     * @return CalculationExecutionState::*|null
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
