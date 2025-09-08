<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Shows the results of the human in the loop evaluation. If there is no HumanLoopArn, the input did not trigger human
 * review.
 */
final class HumanLoopActivationOutput
{
    /**
     * The Amazon Resource Name (ARN) of the HumanLoop created.
     *
     * @var string|null
     */
    private $humanLoopArn;

    /**
     * Shows if and why human review was needed.
     *
     * @var string[]|null
     */
    private $humanLoopActivationReasons;

    /**
     * Shows the result of condition evaluations, including those conditions which activated a human review.
     *
     * @var string|null
     */
    private $humanLoopActivationConditionsEvaluationResults;

    /**
     * @param array{
     *   HumanLoopArn?: string|null,
     *   HumanLoopActivationReasons?: string[]|null,
     *   HumanLoopActivationConditionsEvaluationResults?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->humanLoopArn = $input['HumanLoopArn'] ?? null;
        $this->humanLoopActivationReasons = $input['HumanLoopActivationReasons'] ?? null;
        $this->humanLoopActivationConditionsEvaluationResults = $input['HumanLoopActivationConditionsEvaluationResults'] ?? null;
    }

    /**
     * @param array{
     *   HumanLoopArn?: string|null,
     *   HumanLoopActivationReasons?: string[]|null,
     *   HumanLoopActivationConditionsEvaluationResults?: string|null,
     * }|HumanLoopActivationOutput $input
     */
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
