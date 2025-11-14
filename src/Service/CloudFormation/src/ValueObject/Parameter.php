<?php

namespace AsyncAws\CloudFormation\ValueObject;

/**
 * The `Parameter` data type.
 */
final class Parameter
{
    /**
     * The key associated with the parameter. If you don't specify a key and value for a particular parameter,
     * CloudFormation uses the default value that's specified in your template.
     *
     * @var string|null
     */
    private $parameterKey;

    /**
     * The input value associated with the parameter.
     *
     * @var string|null
     */
    private $parameterValue;

    /**
     * During a stack update, use the existing parameter value that the stack is using for a given parameter key. If you
     * specify `true`, do not specify a parameter value.
     *
     * @var bool|null
     */
    private $usePreviousValue;

    /**
     * Read-only. The value that corresponds to a Systems Manager parameter key. This field is returned only for Systems
     * Manager parameter types in the template. For more information, see Specify existing resources at runtime with
     * CloudFormation-supplied parameter types [^1] in the *CloudFormation User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/cloudformation-supplied-parameter-types.html
     *
     * @var string|null
     */
    private $resolvedValue;

    /**
     * @param array{
     *   ParameterKey?: string|null,
     *   ParameterValue?: string|null,
     *   UsePreviousValue?: bool|null,
     *   ResolvedValue?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->parameterKey = $input['ParameterKey'] ?? null;
        $this->parameterValue = $input['ParameterValue'] ?? null;
        $this->usePreviousValue = $input['UsePreviousValue'] ?? null;
        $this->resolvedValue = $input['ResolvedValue'] ?? null;
    }

    /**
     * @param array{
     *   ParameterKey?: string|null,
     *   ParameterValue?: string|null,
     *   UsePreviousValue?: bool|null,
     *   ResolvedValue?: string|null,
     * }|Parameter $input
     */
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
