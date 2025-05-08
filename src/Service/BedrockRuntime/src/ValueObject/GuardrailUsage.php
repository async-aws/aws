<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The details on the use of the guardrail.
 */
final class GuardrailUsage
{
    /**
     * The topic policy units processed by the guardrail.
     *
     * @var int
     */
    private $topicPolicyUnits;

    /**
     * The content policy units processed by the guardrail.
     *
     * @var int
     */
    private $contentPolicyUnits;

    /**
     * The word policy units processed by the guardrail.
     *
     * @var int
     */
    private $wordPolicyUnits;

    /**
     * The sensitive information policy units processed by the guardrail.
     *
     * @var int
     */
    private $sensitiveInformationPolicyUnits;

    /**
     * The sensitive information policy free units processed by the guardrail.
     *
     * @var int
     */
    private $sensitiveInformationPolicyFreeUnits;

    /**
     * The contextual grounding policy units processed by the guardrail.
     *
     * @var int
     */
    private $contextualGroundingPolicyUnits;

    /**
     * @param array{
     *   topicPolicyUnits: int,
     *   contentPolicyUnits: int,
     *   wordPolicyUnits: int,
     *   sensitiveInformationPolicyUnits: int,
     *   sensitiveInformationPolicyFreeUnits: int,
     *   contextualGroundingPolicyUnits: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->topicPolicyUnits = $input['topicPolicyUnits'] ?? $this->throwException(new InvalidArgument('Missing required field "topicPolicyUnits".'));
        $this->contentPolicyUnits = $input['contentPolicyUnits'] ?? $this->throwException(new InvalidArgument('Missing required field "contentPolicyUnits".'));
        $this->wordPolicyUnits = $input['wordPolicyUnits'] ?? $this->throwException(new InvalidArgument('Missing required field "wordPolicyUnits".'));
        $this->sensitiveInformationPolicyUnits = $input['sensitiveInformationPolicyUnits'] ?? $this->throwException(new InvalidArgument('Missing required field "sensitiveInformationPolicyUnits".'));
        $this->sensitiveInformationPolicyFreeUnits = $input['sensitiveInformationPolicyFreeUnits'] ?? $this->throwException(new InvalidArgument('Missing required field "sensitiveInformationPolicyFreeUnits".'));
        $this->contextualGroundingPolicyUnits = $input['contextualGroundingPolicyUnits'] ?? $this->throwException(new InvalidArgument('Missing required field "contextualGroundingPolicyUnits".'));
    }

    /**
     * @param array{
     *   topicPolicyUnits: int,
     *   contentPolicyUnits: int,
     *   wordPolicyUnits: int,
     *   sensitiveInformationPolicyUnits: int,
     *   sensitiveInformationPolicyFreeUnits: int,
     *   contextualGroundingPolicyUnits: int,
     * }|GuardrailUsage $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContentPolicyUnits(): int
    {
        return $this->contentPolicyUnits;
    }

    public function getContextualGroundingPolicyUnits(): int
    {
        return $this->contextualGroundingPolicyUnits;
    }

    public function getSensitiveInformationPolicyFreeUnits(): int
    {
        return $this->sensitiveInformationPolicyFreeUnits;
    }

    public function getSensitiveInformationPolicyUnits(): int
    {
        return $this->sensitiveInformationPolicyUnits;
    }

    public function getTopicPolicyUnits(): int
    {
        return $this->topicPolicyUnits;
    }

    public function getWordPolicyUnits(): int
    {
        return $this->wordPolicyUnits;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
