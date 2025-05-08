<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * A Top level guardrail trace object. For more information, see ConverseTrace.
 */
final class GuardrailTraceAssessment
{
    /**
     * The output from the model.
     *
     * @var string[]|null
     */
    private $modelOutput;

    /**
     * The input assessment.
     *
     * @var array<string, GuardrailAssessment>|null
     */
    private $inputAssessment;

    /**
     * the output assessments.
     *
     * @var array<string, GuardrailAssessment[]>|null
     */
    private $outputAssessments;

    /**
     * @param array{
     *   modelOutput?: null|string[],
     *   inputAssessment?: null|array<string, GuardrailAssessment|array>,
     *   outputAssessments?: null|array<string, array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->modelOutput = $input['modelOutput'] ?? null;
        $this->inputAssessment = isset($input['inputAssessment']) ? array_map([GuardrailAssessment::class, 'create'], $input['inputAssessment']) : null;
        $this->outputAssessments = $input['outputAssessments'] ?? null;
    }

    /**
     * @param array{
     *   modelOutput?: null|string[],
     *   inputAssessment?: null|array<string, GuardrailAssessment|array>,
     *   outputAssessments?: null|array<string, array>,
     * }|GuardrailTraceAssessment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, GuardrailAssessment>
     */
    public function getInputAssessment(): array
    {
        return $this->inputAssessment ?? [];
    }

    /**
     * @return string[]
     */
    public function getModelOutput(): array
    {
        return $this->modelOutput ?? [];
    }

    /**
     * @return array<string, GuardrailAssessment[]>
     */
    public function getOutputAssessments(): array
    {
        return $this->outputAssessments ?? [];
    }
}
