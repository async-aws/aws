<?php

namespace AsyncAws\CloudFormation\Result;

class Output
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
     *   OutputKey: ?string,
     *   OutputValue: ?string,
     *   Description: ?string,
     *   ExportName: ?string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->OutputKey = $input['OutputKey'];
        $this->OutputValue = $input['OutputValue'];
        $this->Description = $input['Description'];
        $this->ExportName = $input['ExportName'];
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
