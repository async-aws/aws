<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Shows the results of the human in the loop evaluation.
 */
final class HumanLoopActivationOutput
{
    /**
     * The Amazon Resource Name (ARN) of the HumanLoop created.
     */
    private $humanLoopArn;

    /**
     * Shows if and why human review was needed.
     */
    private $humanLoopActivationReasons;

    /**
     * Shows the result of condition evaluations, including those conditions which activated a human review.
     */
    private $humanLoopActivationConditionsEvaluationResults;

    /**
     * @param array{
     *   HumanLoopArn?: null|string,
     *   HumanLoopActivationReasons?: null|string[],
     *   HumanLoopActivationConditionsEvaluationResults?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->humanLoopArn = $input['HumanLoopArn'] ?? null;
        $this->humanLoopActivationReasons = $input['HumanLoopActivationReasons'] ?? null;
        $this->humanLoopActivationConditionsEvaluationResults = $input['HumanLoopActivationConditionsEvaluationResults'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHumanLoopActivationConditionsEvaluationResults(): ?string
    {
        return $this->humanLoopActivationConditionsEvaluationResults;
    }

    /**
     * @return string[]
     */
    public function getHumanLoopActivationReasons(): array
    {
        return $this->humanLoopActivationReasons ?? [];
    }

    public function getHumanLoopArn(): ?string
    {
        return $this->humanLoopArn;
    }
}
