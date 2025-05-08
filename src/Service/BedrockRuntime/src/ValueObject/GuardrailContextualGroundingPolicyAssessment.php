<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The policy assessment details for the guardrails contextual grounding filter.
 */
final class GuardrailContextualGroundingPolicyAssessment
{
    /**
     * The filter details for the guardrails contextual grounding filter.
     *
     * @var GuardrailContextualGroundingFilter[]|null
     */
    private $filters;

    /**
     * @param array{
     *   filters?: null|array<GuardrailContextualGroundingFilter|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->filters = isset($input['filters']) ? array_map([GuardrailContextualGroundingFilter::class, 'create'], $input['filters']) : null;
    }

    /**
     * @param array{
     *   filters?: null|array<GuardrailContextualGroundingFilter|array>,
     * }|GuardrailContextualGroundingPolicyAssessment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailContextualGroundingFilter[]
     */
    public function getFilters(): array
    {
        return $this->filters ?? [];
    }
}
