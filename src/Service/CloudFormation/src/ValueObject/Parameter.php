<?php

namespace AsyncAws\CloudFormation\ValueObject;

/**
 * The Parameter data type.
 */
final class Parameter
{
    /**
     * The key associated with the parameter. If you don't specify a key and value for a particular parameter, AWS
     * CloudFormation uses the default value that is specified in your template.
     */
    private $parameterKey;

    /**
     * The input value associated with the parameter.
     */
    private $parameterValue;

    /**
     * During a stack update, use the existing parameter value that the stack is using for a given parameter key. If you
     * specify `true`, do not specify a parameter value.
     */
    private $usePreviousValue;

    /**
     * Read-only. The value that corresponds to a Systems Manager parameter key. This field is returned only for `SSM`
     * parameter types in the template.
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/parameters-section-structure.html#aws-ssm-parameter-types
     */
    private $resolvedValue;

    /**
     * @param array{
     *   ParameterKey?: null|string,
     *   ParameterValue?: null|string,
     *   UsePreviousValue?: null|bool,
     *   ResolvedValue?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->parameterKey = $input['ParameterKey'] ?? null;
        $this->parameterValue = $input['ParameterValue'] ?? null;
        $this->usePreviousValue = $input['UsePreviousValue'] ?? null;
        $this->resolvedValue = $input['ResolvedValue'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getParameterKey(): ?string
    {
        return $this->parameterKey;
    }

    public function getParameterValue(): ?string
    {
        return $this->parameterValue;
    }

    public function getResolvedValue(): ?string
    {
        return $this->resolvedValue;
    }

    public function getUsePreviousValue(): ?bool
    {
        return $this->usePreviousValue;
    }
}
