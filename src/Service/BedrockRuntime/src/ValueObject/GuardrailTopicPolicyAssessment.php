<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A behavior assessment of a topic policy.
 */
final class GuardrailTopicPolicyAssessment
{
    /**
     * The topics in the assessment.
     *
     * @var GuardrailTopic[]
     */
    private $topics;

    /**
     * @param array{
     *   topics: array<GuardrailTopic|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->topics = isset($input['topics']) ? array_map([GuardrailTopic::class, 'create'], $input['topics']) : $this->throwException(new InvalidArgument('Missing required field "topics".'));
    }

    /**
     * @param array{
     *   topics: array<GuardrailTopic|array>,
     * }|GuardrailTopicPolicyAssessment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
