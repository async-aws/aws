<?php

namespace AsyncAws\CloudFormation\Result;

class Output
{
    private $OutputKey;

    private $OutputValue;

    private $Description;

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

    /**
     * User defined description associated with the output.
     */
    public function getDescription(): ?string
    {
        return $this->Description;
    }

    /**
     * The name of the export associated with the output.
     */
    public function getExportName(): ?string
    {
        return $this->ExportName;
    }

    /**
     * The key associated with the output.
     */
    public function getOutputKey(): ?string
    {
        return $this->OutputKey;
    }

    /**
     * The value associated with the output.
     */
    public function getOutputValue(): ?string
    {
        return $this->OutputValue;
    }
}
