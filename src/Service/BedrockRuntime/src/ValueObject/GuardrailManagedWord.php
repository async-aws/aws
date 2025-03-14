<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\GuardrailManagedWordType;
use AsyncAws\BedrockRuntime\Enum\GuardrailWordPolicyAction;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A managed word configured in a guardrail.
 */
final class GuardrailManagedWord
{
    /**
     * The match for the managed word.
     *
     * @var string
     */
    private $match;

    /**
     * The type for the managed word.
     *
     * @var GuardrailManagedWordType::*
     */
    private $type;

    /**
     * The action for the managed word.
     *
     * @var GuardrailWordPolicyAction::*
     */
    private $action;

    /**
     * @param array{
     *   match: string,
     *   type: GuardrailManagedWordType::*,
     *   action: GuardrailWordPolicyAction::*,
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
     *   type: GuardrailManagedWordType::*,
     *   action: GuardrailWordPolicyAction::*,
     * }|GuardrailManagedWord $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailWordPolicyAction::*
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
     * @return GuardrailManagedWordType::*
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
