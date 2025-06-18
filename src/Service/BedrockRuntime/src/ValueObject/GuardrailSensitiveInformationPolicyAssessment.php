<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The assessment for aPersonally Identifiable Information (PII) policy.
 */
final class GuardrailSensitiveInformationPolicyAssessment
{
    /**
     * The PII entities in the assessment.
     *
     * @var GuardrailPiiEntityFilter[]
     */
    private $piiEntities;

    /**
     * The regex queries in the assessment.
     *
     * @var GuardrailRegexFilter[]
     */
    private $regexes;

    /**
     * @param array{
     *   piiEntities: array<GuardrailPiiEntityFilter|array>,
     *   regexes: array<GuardrailRegexFilter|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->piiEntities = isset($input['piiEntities']) ? array_map([GuardrailPiiEntityFilter::class, 'create'], $input['piiEntities']) : $this->throwException(new InvalidArgument('Missing required field "piiEntities".'));
        $this->regexes = isset($input['regexes']) ? array_map([GuardrailRegexFilter::class, 'create'], $input['regexes']) : $this->throwException(new InvalidArgument('Missing required field "regexes".'));
    }

    /**
     * @param array{
     *   piiEntities: array<GuardrailPiiEntityFilter|array>,
     *   regexes: array<GuardrailRegexFilter|array>,
     * }|GuardrailSensitiveInformationPolicyAssessment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailPiiEntityFilter[]
     */
    public function getPiiEntities(): array
    {
        return $this->piiEntities;
    }

    /**
     * @return GuardrailRegexFilter[]
     */
    public function getRegexes(): array
    {
        return $this->regexes;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
