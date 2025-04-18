<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\GuardrailContentFilterConfidence;
use AsyncAws\BedrockRuntime\Enum\GuardrailContentFilterStrength;
use AsyncAws\BedrockRuntime\Enum\GuardrailContentFilterType;
use AsyncAws\BedrockRuntime\Enum\GuardrailContentPolicyAction;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The content filter for a guardrail.
 */
final class GuardrailContentFilter
{
    /**
     * The guardrail type.
     *
     * @var GuardrailContentFilterType::*
     */
    private $type;

    /**
     * The guardrail confidence.
     *
     * @var GuardrailContentFilterConfidence::*
     */
    private $confidence;

    /**
     * The filter strength setting for the guardrail content filter.
     *
     * @var GuardrailContentFilterStrength::*|null
     */
    private $filterStrength;

    /**
     * The guardrail action.
     *
     * @var GuardrailContentPolicyAction::*
     */
    private $action;

    /**
     * @param array{
     *   type: GuardrailContentFilterType::*,
     *   confidence: GuardrailContentFilterConfidence::*,
     *   filterStrength?: null|GuardrailContentFilterStrength::*,
     *   action: GuardrailContentPolicyAction::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->confidence = $input['confidence'] ?? $this->throwException(new InvalidArgument('Missing required field "confidence".'));
        $this->filterStrength = $input['filterStrength'] ?? null;
        $this->action = $input['action'] ?? $this->throwException(new InvalidArgument('Missing required field "action".'));
    }

    /**
     * @param array{
     *   type: GuardrailContentFilterType::*,
     *   confidence: GuardrailContentFilterConfidence::*,
     *   filterStrength?: null|GuardrailContentFilterStrength::*,
     *   action: GuardrailContentPolicyAction::*,
     * }|GuardrailContentFilter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailContentPolicyAction::*
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return GuardrailContentFilterConfidence::*
     */
    public function getConfidence(): string
    {
        return $this->confidence;
    }

    /**
     * @return GuardrailContentFilterStrength::*|null
     */
    public function getFilterStrength(): ?string
    {
        return $this->filterStrength;
    }

    /**
     * @return GuardrailContentFilterType::*
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
