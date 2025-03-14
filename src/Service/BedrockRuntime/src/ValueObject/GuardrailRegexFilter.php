<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\GuardrailSensitiveInformationPolicyAction;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A Regex filter configured in a guardrail.
 */
final class GuardrailRegexFilter
{
    /**
     * The regex filter name.
     *
     * @var string|null
     */
    private $name;

    /**
     * The regesx filter match.
     *
     * @var string|null
     */
    private $match;

    /**
     * The regex query.
     *
     * @var string|null
     */
    private $regex;

    /**
     * The region filter action.
     *
     * @var GuardrailSensitiveInformationPolicyAction::*
     */
    private $action;

    /**
     * @param array{
     *   name?: null|string,
     *   match?: null|string,
     *   regex?: null|string,
     *   action: GuardrailSensitiveInformationPolicyAction::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
        $this->match = $input['match'] ?? null;
        $this->regex = $input['regex'] ?? null;
        $this->action = $input['action'] ?? $this->throwException(new InvalidArgument('Missing required field "action".'));
    }

    /**
     * @param array{
     *   name?: null|string,
     *   match?: null|string,
     *   regex?: null|string,
     *   action: GuardrailSensitiveInformationPolicyAction::*,
     * }|GuardrailRegexFilter $input
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

    public function getMatch(): ?string
    {
        return $this->match;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRegex(): ?string
    {
        return $this->regex;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
