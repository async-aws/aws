<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The trace object in a response from Converse [^1]. Currently, you can only trace guardrails.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html
 */
final class ConverseTrace
{
    /**
     * The guardrail trace object.
     *
     * @var GuardrailTraceAssessment|null
     */
    private $guardrail;

    /**
     * The request's prompt router.
     *
     * @var PromptRouterTrace|null
     */
    private $promptRouter;

    /**
     * @param array{
     *   guardrail?: null|GuardrailTraceAssessment|array,
     *   promptRouter?: null|PromptRouterTrace|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->guardrail = isset($input['guardrail']) ? GuardrailTraceAssessment::create($input['guardrail']) : null;
        $this->promptRouter = isset($input['promptRouter']) ? PromptRouterTrace::create($input['promptRouter']) : null;
    }

    /**
     * @param array{
     *   guardrail?: null|GuardrailTraceAssessment|array,
     *   promptRouter?: null|PromptRouterTrace|array,
     * }|ConverseTrace $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGuardrail(): ?GuardrailTraceAssessment
    {
        return $this->guardrail;
    }

    public function getPromptRouter(): ?PromptRouterTrace
    {
        return $this->promptRouter;
    }
}
