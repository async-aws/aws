<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The invocation metrics for the guardrail.
 */
final class GuardrailInvocationMetrics
{
    /**
     * The processing latency details for the guardrail invocation metrics.
     *
     * @var int|null
     */
    private $guardrailProcessingLatency;

    /**
     * The usage details for the guardrail invocation metrics.
     *
     * @var GuardrailUsage|null
     */
    private $usage;

    /**
     * The coverage details for the guardrail invocation metrics.
     *
     * @var GuardrailCoverage|null
     */
    private $guardrailCoverage;

    /**
     * @param array{
     *   guardrailProcessingLatency?: null|int,
     *   usage?: null|GuardrailUsage|array,
     *   guardrailCoverage?: null|GuardrailCoverage|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->guardrailProcessingLatency = $input['guardrailProcessingLatency'] ?? null;
        $this->usage = isset($input['usage']) ? GuardrailUsage::create($input['usage']) : null;
        $this->guardrailCoverage = isset($input['guardrailCoverage']) ? GuardrailCoverage::create($input['guardrailCoverage']) : null;
    }

    /**
     * @param array{
     *   guardrailProcessingLatency?: null|int,
     *   usage?: null|GuardrailUsage|array,
     *   guardrailCoverage?: null|GuardrailCoverage|array,
     * }|GuardrailInvocationMetrics $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGuardrailCoverage(): ?GuardrailCoverage
    {
        return $this->guardrailCoverage;
    }

    public function getGuardrailProcessingLatency(): ?int
    {
        return $this->guardrailProcessingLatency;
    }

    public function getUsage(): ?GuardrailUsage
    {
        return $this->usage;
    }
}
