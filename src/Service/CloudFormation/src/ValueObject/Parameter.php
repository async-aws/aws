<?php

namespace AsyncAws\CloudFormation\ValueObject;

final class Parameter
{
    /**
     * The key associated with the parameter. If you don't specify a key and value for a particular parameter, AWS
     * CloudFormation uses the default value that is specified in your template.
     */
    private $ParameterKey;

    /**
     * The input value associated with the parameter.
     */
    private $ParameterValue;

    /**
     * During a stack update, use the existing parameter value that the stack is using for a given parameter key. If you
     * specify `true`, do not specify a parameter value.
     */
    private $UsePreviousValue;

    /**
     * Read-only. The value that corresponds to a Systems Manager parameter key. This field is returned only for `SSM`
     * parameter types in the template.
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/parameters-section-structure.html#aws-ssm-parameter-types
     */
    private $ResolvedValue;

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
        $this->ParameterKey = $input['ParameterKey'] ?? null;
        $this->ParameterValue = $input['ParameterValue'] ?? null;
        $this->UsePreviousValue = $input['UsePreviousValue'] ?? null;
        $this->ResolvedValue = $input['ResolvedValue'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getParameterKey(): ?string
    {
        return $this->ParameterKey;
    }

    public function getParameterValue(): ?string
    {
        return $this->ParameterValue;
    }

    public function getResolvedValue(): ?string
    {
        return $this->ResolvedValue;
    }

    public function getUsePreviousValue(): ?bool
    {
        return $this->UsePreviousValue;
    }
}
