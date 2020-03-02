<?php

namespace AsyncAws\CloudFormation\Result;

class Parameter
{
    private $ParameterKey;

    private $ParameterValue;

    private $UsePreviousValue;

    private $ResolvedValue;

    /**
     * @param array{
     *   ParameterKey: null|string,
     *   ParameterValue: null|string,
     *   UsePreviousValue: null|bool,
     *   ResolvedValue: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ParameterKey = $input['ParameterKey'];
        $this->ParameterValue = $input['ParameterValue'];
        $this->UsePreviousValue = $input['UsePreviousValue'];
        $this->ResolvedValue = $input['ResolvedValue'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * The key associated with the parameter. If you don't specify a key and value for a particular parameter, AWS
     * CloudFormation uses the default value that is specified in your template.
     */
    public function getParameterKey(): ?string
    {
        return $this->ParameterKey;
    }

    /**
     * The input value associated with the parameter.
     */
    public function getParameterValue(): ?string
    {
        return $this->ParameterValue;
    }

    /**
     * Read-only. The value that corresponds to a Systems Manager parameter key. This field is returned only for `SSM`
     * parameter types in the template.
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/parameters-section-structure.html#aws-ssm-parameter-types
     */
    public function getResolvedValue(): ?string
    {
        return $this->ResolvedValue;
    }

    /**
     * During a stack update, use the existing parameter value that the stack is using for a given parameter key. If you
     * specify `true`, do not specify a parameter value.
     */
    public function getUsePreviousValue(): ?bool
    {
        return $this->UsePreviousValue;
    }
}
