<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * An assessment of a content policy for a guardrail.
 */
final class GuardrailContentPolicyAssessment
{
    /**
     * The content policy filters.
     *
     * @var GuardrailContentFilter[]
     */
    private $filters;

    /**
     * @param array{
     *   filters: array<GuardrailContentFilter|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->filters = isset($input['filters']) ? array_map([GuardrailContentFilter::class, 'create'], $input['filters']) : $this->throwException(new InvalidArgument('Missing required field "filters".'));
    }

    /**
     * @param array{
     *   filters: array<GuardrailContentFilter|array>,
     * }|GuardrailContentPolicyAssessment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailContentFilter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
