<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\GuardrailPiiEntityType;
use AsyncAws\BedrockRuntime\Enum\GuardrailSensitiveInformationPolicyAction;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A Personally Identifiable Information (PII) entity configured in a guardrail.
 */
final class GuardrailPiiEntityFilter
{
    /**
     * The PII entity filter match.
     *
     * @var string
     */
    private $match;

    /**
     * The PII entity filter type.
     *
     * @var GuardrailPiiEntityType::*
     */
    private $type;

    /**
     * The PII entity filter action.
     *
     * @var GuardrailSensitiveInformationPolicyAction::*
     */
    private $action;

    /**
     * @param array{
     *   match: string,
     *   type: GuardrailPiiEntityType::*,
     *   action: GuardrailSensitiveInformationPolicyAction::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->match = $input['match'] ?? $this->throwException(new InvalidArgument('Missing required field "match".'));
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->action = $input['action'] ?? $this->throwException(new InvalidArgument('Missing required field "action".'));
    }

    /**
     * @param array{
     *   match: string,
     *   type: GuardrailPiiEntityType::*,
     *   action: GuardrailSensitiveInformationPolicyAction::*,
     * }|GuardrailPiiEntityFilter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailSensitiveInformationPolicyAction::*
     */
    public function getAction(): string
    {
        return $this->action;
    }

    public function getMatch(): string
    {
        return $this->match;
    }

    /**
     * @return GuardrailPiiEntityType::*
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
