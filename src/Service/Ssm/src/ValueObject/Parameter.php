<?php

namespace AsyncAws\Ssm\ValueObject;

use AsyncAws\Ssm\Enum\ParameterType;

/**
 * Information about a parameter.
 */
final class Parameter
{
    /**
     * The name of the parameter.
     */
    private $name;

    /**
     * The type of parameter. Valid values include the following: `String`, `StringList`, and `SecureString`.
     */
    private $type;

    /**
     * The parameter value.
     */
    private $value;

    /**
     * The parameter version.
     */
    private $version;

    /**
     * Either the version number or the label used to retrieve the parameter value. Specify selectors by using one of the
     * following formats:.
     */
    private $selector;

    /**
     * Applies to parameters that reference information in other Amazon Web Services services. `SourceResult` is the raw
     * result or response from the source.
     */
    private $sourceResult;

    /**
     * Date the parameter was last changed or updated and the parameter version was created.
     */
    private $lastModifiedDate;

    /**
     * The Amazon Resource Name (ARN) of the parameter.
     */
    private $arn;

    /**
     * The data type of the parameter, such as `text` or `aws:ec2:image`. The default is `text`.
     */
    private $dataType;

    /**
     * @param array{
     *   Name?: null|string,
     *   Type?: null|ParameterType::*,
     *   Value?: null|string,
     *   Version?: null|string,
     *   Selector?: null|string,
     *   SourceResult?: null|string,
     *   LastModifiedDate?: null|\DateTimeImmutable,
     *   ARN?: null|string,
     *   DataType?: null|string,
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

    public function getVersion(): ?string
    {
        return $this->version;
    }
}
