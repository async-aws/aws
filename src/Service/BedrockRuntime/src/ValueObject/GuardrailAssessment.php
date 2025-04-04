<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * A behavior assessment of the guardrail policies used in a call to the Converse API.
 */
final class GuardrailAssessment
{
    /**
     * The topic policy.
     *
     * @var GuardrailTopicPolicyAssessment|null
     */
    private $topicPolicy;

    /**
     * The content policy.
     *
     * @var GuardrailContentPolicyAssessment|null
     */
    private $contentPolicy;

    /**
     * The word policy.
     *
     * @var GuardrailWordPolicyAssessment|null
     */
    private $wordPolicy;

    /**
     * The sensitive information policy.
     *
     * @var GuardrailSensitiveInformationPolicyAssessment|null
     */
    private $sensitiveInformationPolicy;

    /**
     * The contextual grounding policy used for the guardrail assessment.
     *
     * @var GuardrailContextualGroundingPolicyAssessment|null
     */
    private $contextualGroundingPolicy;

    /**
     * The invocation metrics for the guardrail assessment.
     *
     * @var GuardrailInvocationMetrics|null
     */
    private $invocationMetrics;

    /**
     * @param array{
     *   topicPolicy?: null|GuardrailTopicPolicyAssessment|array,
     *   contentPolicy?: null|GuardrailContentPolicyAssessment|array,
     *   wordPolicy?: null|GuardrailWordPolicyAssessment|array,
     *   sensitiveInformationPolicy?: null|GuardrailSensitiveInformationPolicyAssessment|array,
     *   contextualGroundingPolicy?: null|GuardrailContextualGroundingPolicyAssessment|array,
     *   invocationMetrics?: null|GuardrailInvocationMetrics|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->topicPolicy = isset($input['topicPolicy']) ? GuardrailTopicPolicyAssessment::create($input['topicPolicy']) : null;
        $this->contentPolicy = isset($input['contentPolicy']) ? GuardrailContentPolicyAssessment::create($input['contentPolicy']) : null;
        $this->wordPolicy = isset($input['wordPolicy']) ? GuardrailWordPolicyAssessment::create($input['wordPolicy']) : null;
        $this->sensitiveInformationPolicy = isset($input['sensitiveInformationPolicy']) ? GuardrailSensitiveInformationPolicyAssessment::create($input['sensitiveInformationPolicy']) : null;
        $this->contextualGroundingPolicy = isset($input['contextualGroundingPolicy']) ? GuardrailContextualGroundingPolicyAssessment::create($input['contextualGroundingPolicy']) : null;
        $this->invocationMetrics = isset($input['invocationMetrics']) ? GuardrailInvocationMetrics::create($input['invocationMetrics']) : null;
    }

    /**
     * @param array{
     *   topicPolicy?: null|GuardrailTopicPolicyAssessment|array,
     *   contentPolicy?: null|GuardrailContentPolicyAssessment|array,
     *   wordPolicy?: null|GuardrailWordPolicyAssessment|array,
     *   sensitiveInformationPolicy?: null|GuardrailSensitiveInformationPolicyAssessment|array,
     *   contextualGroundingPolicy?: null|GuardrailContextualGroundingPolicyAssessment|array,
     *   invocationMetrics?: null|GuardrailInvocationMetrics|array,
     * }|GuardrailAssessment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContentPolicy(): ?GuardrailContentPolicyAssessment
    {
        return $this->contentPolicy;
    }

    public function getContextualGroundingPolicy(): ?GuardrailContextualGroundingPolicyAssessment
    {
        return $this->contextualGroundingPolicy;
    }

    public function getInvocationMetrics(): ?GuardrailInvocationMetrics
    {
        return $this->invocationMetrics;
    }

    public function getSensitiveInformationPolicy(): ?GuardrailSensitiveInformationPolicyAssessment
    {
        return $this->sensitiveInformationPolicy;
    }

    public function getTopicPolicy(): ?GuardrailTopicPolicyAssessment
    {
        return $this->topicPolicy;
    }

    public function getWordPolicy(): ?GuardrailWordPolicyAssessment
    {
        return $this->wordPolicy;
    }
}
