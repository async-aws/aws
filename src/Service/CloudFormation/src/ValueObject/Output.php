<?php

namespace AsyncAws\CloudFormation\ValueObject;

/**
 * The Output data type.
 */
final class Output
{
    /**
     * The key associated with the output.
     */
    private $outputKey;

    /**
     * The value associated with the output.
     */
    private $outputValue;

    /**
     * User defined description associated with the output.
     */
    private $description;

    /**
     * The name of the export associated with the output.
     */
    private $exportName;

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
        $this->outputKey = $input['OutputKey'] ?? null;
        $this->outputValue = $input['OutputValue'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->exportName = $input['ExportName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getExportName(): ?string
    {
        return $this->exportName;
    }

    public function getOutputKey(): ?string
    {
        return $this->outputKey;
    }

    public function getOutputValue(): ?string
    {
        return $this->outputValue;
    }
}
