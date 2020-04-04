<?php

namespace AsyncAws\CloudFormation\ValueObject;

final class Output
{
    /**
     * The key associated with the output.
     */
    private $OutputKey;

    /**
     * The value associated with the output.
     */
    private $OutputValue;

    /**
     * User defined description associated with the output.
     */
    private $Description;

    /**
     * The name of the export associated with the output.
     */
    private $ExportName;

    /**
     * @param array{
     *   OutputKey?: null|string,
     *   OutputValue?: null|string,
     *   Description?: null|string,
     *   ExportName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->OutputKey = $input['OutputKey'] ?? null;
        $this->OutputValue = $input['OutputValue'] ?? null;
        $this->Description = $input['Description'] ?? null;
        $this->ExportName = $input['ExportName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function getExportName(): ?string
    {
        return $this->ExportName;
    }

    public function getOutputKey(): ?string
    {
        return $this->OutputKey;
    }

    public function getOutputValue(): ?string
    {
        return $this->OutputValue;
    }
}
