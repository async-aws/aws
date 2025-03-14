<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\GuardrailTopicPolicyAction;
use AsyncAws\BedrockRuntime\Enum\GuardrailTopicType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about a topic guardrail.
 */
final class GuardrailTopic
{
    /**
     * The name for the guardrail.
     *
     * @var string
     */
    private $name;

    /**
     * The type behavior that the guardrail should perform when the model detects the topic.
     *
     * @var GuardrailTopicType::*
     */
    private $type;

    /**
     * The action the guardrail should take when it intervenes on a topic.
     *
     * @var GuardrailTopicPolicyAction::*
     */
    private $action;

    /**
     * @param array{
     *   name: string,
     *   type: GuardrailTopicType::*,
     *   action: GuardrailTopicPolicyAction::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->action = $input['action'] ?? $this->throwException(new InvalidArgument('Missing required field "action".'));
    }

    /**
     * @param array{
     *   name: string,
     *   type: GuardrailTopicType::*,
     *   action: GuardrailTopicPolicyAction::*,
     * }|GuardrailTopic $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailTopicPolicyAction::*
     */
    public function getAction(): string
    {
        return $this->action;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return GuardrailTopicType::*
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
