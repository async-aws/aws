<?php

namespace AsyncAws\CloudFormation\ValueObject;

/**
 * The `Export` structure describes the exported output values for a stack.
 *
 * For more information, see Get exported outputs from a deployed CloudFormation stack [^1].
 *
 * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-stack-exports.html
 */
final class Export
{
    /**
     * The stack that contains the exported output name and value.
     *
     * @var string|null
     */
    private $exportingStackId;

    /**
     * The name of exported output value. Use this name and the `Fn::ImportValue` function to import the associated value
     * into other stacks. The name is defined in the `Export` field in the associated stack's `Outputs` section.
     *
     * @var string|null
     */
    private $name;

    /**
     * The value of the exported output, such as a resource physical ID. This value is defined in the `Export` field in the
     * associated stack's `Outputs` section.
     *
     * @var string|null
     */
    private $value;

    /**
     * @param array{
     *   ExportingStackId?: string|null,
     *   Name?: string|null,
     *   Value?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->exportingStackId = $input['ExportingStackId'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->value = $input['Value'] ?? null;
    }

    /**
     * @param array{
     *   ExportingStackId?: string|null,
     *   Name?: string|null,
     *   Value?: string|null,
     * }|Export $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExportingStackId(): ?string
    {
        return $this->exportingStackId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
