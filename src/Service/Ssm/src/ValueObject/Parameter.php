<?php

namespace AsyncAws\Ssm\ValueObject;

use AsyncAws\Ssm\Enum\ParameterType;

/**
 * An Amazon Web Services Systems Manager parameter in Parameter Store.
 */
final class Parameter
{
    /**
     * The name of the parameter.
     *
     * @var string|null
     */
    private $name;

    /**
     * The type of parameter. Valid values include the following: `String`, `StringList`, and `SecureString`.
     *
     * > If type is `StringList`, the system returns a comma-separated string with no spaces between commas in the `Value`
     * > field.
     *
     * @var ParameterType::*|null
     */
    private $type;

    /**
     * The parameter value.
     *
     * > If type is `StringList`, the system returns a comma-separated string with no spaces between commas in the `Value`
     * > field.
     *
     * @var string|null
     */
    private $value;

    /**
     * The parameter version.
     *
     * @var int|null
     */
    private $version;

    /**
     * Either the version number or the label used to retrieve the parameter value. Specify selectors by using one of the
     * following formats:
     *
     * parameter_name:version
     *
     * parameter_name:label
     *
     * @var string|null
     */
    private $selector;

    /**
     * Applies to parameters that reference information in other Amazon Web Services services. `SourceResult` is the raw
     * result or response from the source.
     *
     * @var string|null
     */
    private $sourceResult;

    /**
     * Date the parameter was last changed or updated and the parameter version was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModifiedDate;

    /**
     * The Amazon Resource Name (ARN) of the parameter.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The data type of the parameter, such as `text` or `aws:ec2:image`. The default is `text`.
     *
     * @var string|null
     */
    private $dataType;

    /**
     * @param array{
     *   Name?: string|null,
     *   Type?: ParameterType::*|null,
     *   Value?: string|null,
     *   Version?: int|null,
     *   Selector?: string|null,
     *   SourceResult?: string|null,
     *   LastModifiedDate?: \DateTimeImmutable|null,
     *   ARN?: string|null,
     *   DataType?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->type = $input['Type'] ?? null;
        $this->value = $input['Value'] ?? null;
        $this->version = $input['Version'] ?? null;
        $this->selector = $input['Selector'] ?? null;
        $this->sourceResult = $input['SourceResult'] ?? null;
        $this->lastModifiedDate = $input['LastModifiedDate'] ?? null;
        $this->arn = $input['ARN'] ?? null;
        $this->dataType = $input['DataType'] ?? null;
    }

    /**
     * @param array{
     *   Name?: string|null,
     *   Type?: ParameterType::*|null,
     *   Value?: string|null,
     *   Version?: int|null,
     *   Selector?: string|null,
     *   SourceResult?: string|null,
     *   LastModifiedDate?: \DateTimeImmutable|null,
     *   ARN?: string|null,
     *   DataType?: string|null,
     * }|Parameter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getDataType(): ?string
    {
        return $this->dataType;
    }

    public function getLastModifiedDate(): ?\DateTimeImmutable
    {
        return $this->lastModifiedDate;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSelector(): ?string
    {
        return $this->selector;
    }

    public function getSourceResult(): ?string
    {
        return $this->sourceResult;
    }

    /**
     * @return ParameterType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }
}
