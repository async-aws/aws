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
    private $Name;

    /**
     * The type of parameter. Valid values include the following: `String`, `StringList`, and `SecureString`.
     */
    private $Type;

    /**
     * The parameter value.
     */
    private $Value;

    /**
     * The parameter version.
     */
    private $Version;

    /**
     * Either the version number or the label used to retrieve the parameter value. Specify selectors by using one of the
     * following formats:.
     */
    private $Selector;

    /**
     * Applies to parameters that reference information in other AWS services. SourceResult is the raw result or response
     * from the source.
     */
    private $SourceResult;

    /**
     * Date the parameter was last changed or updated and the parameter version was created.
     */
    private $LastModifiedDate;

    /**
     * The Amazon Resource Name (ARN) of the parameter.
     */
    private $ARN;

    /**
     * The data type of the parameter, such as `text` or `aws:ec2:image`. The default is `text`.
     */
    private $DataType;

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
        $this->Name = $input['Name'] ?? null;
        $this->Type = $input['Type'] ?? null;
        $this->Value = $input['Value'] ?? null;
        $this->Version = $input['Version'] ?? null;
        $this->Selector = $input['Selector'] ?? null;
        $this->SourceResult = $input['SourceResult'] ?? null;
        $this->LastModifiedDate = $input['LastModifiedDate'] ?? null;
        $this->ARN = $input['ARN'] ?? null;
        $this->DataType = $input['DataType'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getARN(): ?string
    {
        return $this->ARN;
    }

    public function getDataType(): ?string
    {
        return $this->DataType;
    }

    public function getLastModifiedDate(): ?\DateTimeImmutable
    {
        return $this->LastModifiedDate;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function getSelector(): ?string
    {
        return $this->Selector;
    }

    public function getSourceResult(): ?string
    {
        return $this->SourceResult;
    }

    /**
     * @return ParameterType::*|null
     */
    public function getType(): ?string
    {
        return $this->Type;
    }

    public function getValue(): ?string
    {
        return $this->Value;
    }

    public function getVersion(): ?string
    {
        return $this->Version;
    }
}
