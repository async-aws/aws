<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\GuardrailWordPolicyAction;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A custom word configured in a guardrail.
 */
final class GuardrailCustomWord
{
    /**
     * The match for the custom word.
     *
     * @var string
     */
    private $match;

    /**
     * The action for the custom word.
     *
     * @var GuardrailWordPolicyAction::*
     */
    private $action;

    /**
     * @param array{
     *   match: string,
     *   action: GuardrailWordPolicyAction::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->match = $input['match'] ?? $this->throwException(new InvalidArgument('Missing required field "match".'));
        $this->action = $input['action'] ?? $this->throwException(new InvalidArgument('Missing required field "action".'));
    }

    /**
     * @param array{
     *   match: string,
     *   action: GuardrailWordPolicyAction::*,
     * }|GuardrailCustomWord $input
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
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
