<?php

namespace AsyncAws\CloudFormation\ValueObject;

/**
 * The Output data type.
 */
final class Output
{
    /**
     * The key associated with the output.
     *
     * @var string|null
     */
    private $outputKey;

    /**
     * The value associated with the output.
     *
     * @var string|null
     */
    private $outputValue;

    /**
     * User defined description associated with the output.
     *
     * @var string|null
     */
    private $description;

    /**
     * The name of the export associated with the output.
     *
     * @var string|null
     */
    private $exportName;

    /**
     * @param array{
     *   OutputKey?: string|null,
     *   OutputValue?: string|null,
     *   Description?: string|null,
     *   ExportName?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->outputKey = $input['OutputKey'] ?? null;
        $this->outputValue = $input['OutputValue'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->exportName = $input['ExportName'] ?? null;
    }

    /**
     * @param array{
     *   OutputKey?: string|null,
     *   OutputValue?: string|null,
     *   Description?: string|null,
     *   ExportName?: string|null,
     * }|Output $input
     */
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
