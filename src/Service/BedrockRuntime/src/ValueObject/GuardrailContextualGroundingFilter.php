<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\GuardrailContextualGroundingFilterType;
use AsyncAws\BedrockRuntime\Enum\GuardrailContextualGroundingPolicyAction;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The details for the guardrails contextual grounding filter.
 */
final class GuardrailContextualGroundingFilter
{
    /**
     * The contextual grounding filter type.
     *
     * @var GuardrailContextualGroundingFilterType::*
     */
    private $type;

    /**
     * The threshold used by contextual grounding filter to determine whether the content is grounded or not.
     *
     * @var float
     */
    private $threshold;

    /**
     * The score generated by contextual grounding filter.
     *
     * @var float
     */
    private $score;

    /**
     * The action performed by the guardrails contextual grounding filter.
     *
     * @var GuardrailContextualGroundingPolicyAction::*
     */
    private $action;

    /**
     * @param array{
     *   type: GuardrailContextualGroundingFilterType::*,
     *   threshold: float,
     *   score: float,
     *   action: GuardrailContextualGroundingPolicyAction::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->threshold = $input['threshold'] ?? $this->throwException(new InvalidArgument('Missing required field "threshold".'));
        $this->score = $input['score'] ?? $this->throwException(new InvalidArgument('Missing required field "score".'));
        $this->action = $input['action'] ?? $this->throwException(new InvalidArgument('Missing required field "action".'));
    }

    /**
     * @param array{
     *   type: GuardrailContextualGroundingFilterType::*,
     *   threshold: float,
     *   score: float,
     *   action: GuardrailContextualGroundingPolicyAction::*,
     * }|GuardrailContextualGroundingFilter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailContextualGroundingPolicyAction::*
     */
    public function getAction(): string
    {
        return $this->action;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function getThreshold(): float
    {
        return $this->threshold;
    }

    /**
     * @return GuardrailContextualGroundingFilterType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
